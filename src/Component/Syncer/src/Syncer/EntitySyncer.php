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
        private readonly PersistenceInterface   $persistence,
        private readonly DataManager            $dataManager,
        private readonly ConnectionsBuilder     $connectionsBuilder,
        private readonly DocumentBuilder        $documentBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    )
    {
    }

    private array $entitiesRelated = [];

    public function syncEntitiesEvent(SyncEntitiesEvent $event): void
    {
        $documents = [];

        $events = $this->excludeDuplicatedEvents($event->getSyncEntityEvents());
        $entities = $this->fetchEntitiesFromEvents($events);
        $entitiesRelated = $this->fetchEntitiesRelated($entities);

        $documents = $this->prepareDocuments($documents, $entities);
        $documents = $this->prepareDocuments($documents, $entitiesRelated);
        $documents = $this->prepareDocuments($documents, $this->entitiesRelated);

        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

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

        $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);

        $related[$className][$identifierValue] = $entity;
        $entitiesRelated =  $this->fetchEntitiesRelated($related);

        $this->entitiesRelated = array_merge($this->entitiesRelated, $entitiesRelated);
    }

    /** @param $events SyncEntityEvent[] */
    private function excludeDuplicatedEvents(array $events): array
    {
        $eventsFiltered = [];

        foreach ($events as $event) {
            $hash = $event->getClassName() . '_' . $event->getIdentifierValue();
            $eventsFiltered[$hash] = $event;
        }

        return $eventsFiltered;
    }

    /** @param $events SyncEntityEvent[] */
    private function fetchEntitiesFromEvents(array $events): array
    {
        $entities = [];

        foreach ($events as $event) {
            $className = $event->getClassName();
            $identifierValue = $event->getIdentifierValue();

            // refresh entity before calculating data for storing
            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);

            $entities[$className][$identifierValue] = $entity;
        }

        return $entities;
    }


    private function fetchEntitiesRelated(array $entities): array
    {
        $entitiesRelated = [];

        $connections = $this->connectionsBuilder->build();
        $connection = $connections[0];

        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue => $entity) {
                if ($entity) {
                    $entitiesRelatedRow = $this->entitiesRelatedBuilder->build($connection, $entity);

                    foreach ($entitiesRelatedRow as $entityRelatedRow) {
                        $className = $this->persistence->getRealClassName($entityRelatedRow::class);
                        $identifierValue = $this->persistence->getIdentifierValue($entityRelatedRow);

                        $entitiesRelated[$className][$identifierValue] = $entityRelatedRow;
                    }
                }
            }
        }

        return $entitiesRelated;
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
