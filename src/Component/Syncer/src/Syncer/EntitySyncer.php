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

        $events = $event->getSyncEntityEvents();
        $events = $this->excludeDuplicatedEvents($events);

        $this->prepareDocuments($documents, $events);
        $this->prepareDocumentsForEntitiesRelated($documents);

        $documentsGrouped = (new DocumentGrouper())->groupDocuments($documents);

        $this->dataManager->syncDocuments($documentsGrouped);
    }

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        if (ChangedEntityEvent::TYPE_DELETE_PRE !== $event->getType()) {
            return;
        }

        // for deleting we must prepare related entities immediately because later after flush entity will not exist anymore, and we will be not able to fetch related entities

        $this->prepareEntitiesRelated($event->getClassName(), $event->getIdentifierValue(), true);
    }

    /** @param $events SyncEntityEvent[]  */
    private function excludeDuplicatedEvents(array $events): array
    {
        $eventsFiltered = [];

        foreach ($events as $event) {
            $hash = $event->getClassName() . '_' . $event->getIdentifierValue();
            $eventsFiltered[$hash] = $event;
        }

        return $eventsFiltered;
    }

    private function prepareDocuments(array &$documents, array $changedEntityEvents): void
    {
        foreach ($changedEntityEvents as $event) {
            $className = $event->getClassName();
            $identifierValue = $event->getIdentifierValue();

            // refresh entity before calculating data for storing
            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);

            $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
            foreach ($indexes as $index) {
                // prepare document for search engine sync
                $documents[] = $this->documentBuilder->buildForEntity($index, $entity, $className, $identifierValue);
            }

            if ($entity) {
                // for create and update calculate related entities, for delete are calculated before
                $this->prepareEntitiesRelated($event->getClassName(), $event->getIdentifierValue(), false);
            }
        }
    }

    private function prepareEntitiesRelated(string $className, mixed $identifierValue, bool $delete): void
    {
        $connections = $this->connectionsBuilder->build();

        foreach ($connections as $connection) {
            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);
            $entitiesRelatedPreDelete = $this->entitiesRelatedBuilder->build($connection, $entity, []);

            $this->entitiesRelated[$connection->getName()][$className][$identifierValue] = [];
            foreach ($entitiesRelatedPreDelete as $entity) {
                $this->entitiesRelated[$connection->getName()][$className][$identifierValue][] = [
                    'entity' => $entity,
                    'delete' => $delete,
                ];
            }
        }
    }

    private function prepareDocumentsForEntitiesRelated(&$documents): void
    {
        foreach ($this->entitiesRelated as $connectionName => $connections) {
            foreach ($connections as $className => $identifierValues) {
                foreach ($identifierValues as $identifierValue => $entities) {
                    foreach ($entities as $data) {
                        $entity = $data['entity'];
                        $delete = $data['delete'];

                        if ($delete) {
                            $entity = $this->persistence->refresh($entity);
                        }

                        if ($entity) {
                            $className = $this->persistence->getRealClassName($entity::class);
                            $identifierValue = $this->persistence->getIdentifierValue($entity);

                            $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
                            foreach ($indexes as $index) {
                                $documents[] = $this->documentBuilder->buildForEntity($index, $entity, $className, $identifierValue);
                            }
                        }
                    }
                }
            }
        }

        $this->entitiesRelated = [];
    }
}
