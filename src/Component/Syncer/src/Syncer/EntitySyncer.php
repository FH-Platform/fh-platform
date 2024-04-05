<?php

namespace FHPlatform\Component\Syncer\Syncer;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\EventManager\Event\SyncEntityEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
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

    private array $entitiesRelatedPreDelete = [];

    public function syncEntitiesEvent(SyncEntitiesEvent $event): void
    {
        $documents = [];

        // make events unique per className => $identifierValue
        $events = $this->excludeDuplicatedEvents($event->getSyncEntityEvents());

        // fetch entities and related entities array
        $entities = $this->fetchEntitiesAndRelatedEntitiesFromEvents($events);

        // fetch related entities pre delete
        $entitiesRelatedPreDelete = $this->fetchEntitiesRelatedPreDelete();

        // merge
        $entities = array_merge($entities, $entitiesRelatedPreDelete);

        // refresh all entities from entities array
        $entities = $this->refreshEntities($entities);

        // prepare documents for each entity
        $documents = $this->prepareDocuments($documents, $entities);

        // group documents by connection and index
        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

        // sync documents
        $this->dataManager->syncDocuments($documentsGrouped);
    }

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        if (ChangedEntityEvent::TYPE_DELETE_PRE !== $event->getType()) {
            return;
        }

        // for deleting we must prepare related entities immediately because later after flush entity will not exist anymore, and we will be not able to fetch related entities

        $className = $event->getClassName();
        $identifierValue = $event->getIdentifierValue();

        if (!($entity = $this->persistence->refreshByClassNameId($className, $identifierValue))) {
            return;
        }

        // TODO
        $connections = $this->connectionsBuilder->build();
        $connection = $connections[0];

        $entitiesRelated = $this->entitiesRelatedBuilder->build($connection, $entity);

        foreach ($entitiesRelated as $entityRelated) {
            $className = $this->persistence->getRealClassName($entityRelated::class);
            $identifierValue = $this->persistence->getIdentifierValue($entityRelated);

            $this->entitiesRelatedPreDelete[$className][$identifierValue] = true;
        }
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

    private function fetchEntitiesAndRelatedEntitiesFromEvents(array $events): array
    {
        $entitiesRelated = [];

        $connections = $this->connectionsBuilder->build();
        $connection = $connections[0];

        foreach ($events as $event) {
            $className = $event->getClassName();
            $identifierValue = $event->getIdentifierValue();

            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);
            $entitiesRelated[$className][$identifierValue] = true;

            if ($entity) {
                $entitiesRelatedRow = $this->entitiesRelatedBuilder->build($connection, $entity);

                foreach ($entitiesRelatedRow as $entityRelatedRow) {
                    $className = $this->persistence->getRealClassName($entityRelatedRow::class);
                    $identifierValue = $this->persistence->getIdentifierValue($entityRelatedRow);

                    $entitiesRelated[$className][$identifierValue] = true;
                }
            }
        }

        return $entitiesRelated;
    }

    private function fetchEntitiesRelatedPreDelete(): array
    {
        $entitiesRelatedPreDelete = $this->entitiesRelatedPreDelete;
        foreach ($entitiesRelatedPreDelete as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue => $entity) {
                // pre deleted related entities must be refreshed before creating data because they are generated before flush
                $entitiesRelatedPreDelete[$className][$identifierValue] = $this->persistence->refresh($entity);
            }
        }
        $this->entitiesRelatedPreDelete = [];

        return $entitiesRelatedPreDelete;
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
