<?php

namespace FHPlatform\Component\Syncer\Syncer;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\PersistenceManager\Event\SyncEntityEvent;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\Syncer\DocumentGrouper;

class EntitySyncer
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly DataManager $dataManager,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly DocumentBuilder $documentBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    ) {
    }

    public function syncEntitiesEvent(SyncEntitiesEvent $event): void
    {
        $documents = [];

        // make events unique per className => $identifierValue
        $events = $this->excludeDuplicatedEvents($event->getSyncEntityEvents());

        // fetch entities and related entities array
        $entities = $this->fetchEntitiesAndRelatedEntitiesFromEvents($events);

        // refresh all entities from entities array
        $entities = $this->refreshEntities($entities);

        // prepare documents for each entity
        $documents = $this->prepareDocuments($documents, $entities);

        // group documents by connection and index
        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

        // sync documents
        $this->dataManager->syncDocuments($documentsGrouped);
    }

    /** @param $events SyncEntityEvent[] */
    private function excludeDuplicatedEvents(array $events): array
    {
        $eventsFiltered = [];

        foreach ($events as $event) {
            $hash = $event->getClassName().'_'.$event->getIdentifierValue();
            $eventsFiltered[$hash] = $event;
        }

        return $eventsFiltered;
    }

    /** @param SyncEntityEvent[] $events */
    private function fetchEntitiesAndRelatedEntitiesFromEvents(array $events): array
    {
        $entitiesRelated = [];

        foreach ($events as $event) {
            $className = $event->getClassName();
            $identifierValue = $event->getIdentifierValue();
            $changedFields = $event->getChangedFields(); // TODO

            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);
            $entitiesRelated[$className][$identifierValue] = true;
            if ($entity) {
                $entitiesRelatedRow = $this->entitiesRelatedBuilder->build($entity);

                foreach ($entitiesRelatedRow as $entityRelatedRow) {
                    $className = $this->persistence->getRealClassName($entityRelatedRow::class);
                    $identifierValue = $this->persistence->getIdentifierValue($entityRelatedRow);

                    $entitiesRelated[$className][$identifierValue] = true;
                }
            }
        }

        return $entitiesRelated;
    }

    private function refreshEntities($entities): array
    {
        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue => $entity) {
                $entities[$className][$identifierValue] = $this->persistence->refreshByClassNameId($className, $identifierValue);
            }
        }

        return $entities;
    }

    private function prepareDocuments(array $documents, array $entities): array
    {
        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue => $entity) {
                $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
                foreach ($indexes as $index) {
                    // prepare document for search engine sync
                    $documents[] = $this->documentBuilder->buildForEntity($index, $entity, $className, $identifierValue);
                }
            }
        }

        return $documents;
    }
}
