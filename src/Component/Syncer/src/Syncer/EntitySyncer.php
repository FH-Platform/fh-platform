<?php

namespace FHPlatform\Component\Syncer\Syncer;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
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

    private array $entitiesRelated = [];

    public function syncEntitiesEvent(SyncEntitiesEvent $event): void
    {
        $documents = $this->prepareDocuments($event->getChangedEntityEvents());

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

    private function prepareDocuments(array $changedEntityEvents): array
    {
        $documents = [];
        foreach ($changedEntityEvents as $event) {
            $className = $event->getClassName();
            $identifierValue = $event->getIdentifierValue();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            // refresh entity before calculating data for storing
            $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);

            // prepare document for search engine sync
            $documents[] = $this->documentBuilder->buildForEntity($entity, $className, $identifierValue, $type);

            if ($entity) {
                // for create and update calculate related entities, for delete are calculated before
                $this->prepareEntitiesRelated($event->getClassName(), $event->getIdentifierValue(), false);
            }
        }

        foreach ($this->entitiesRelated as $className => $identifierValues) {
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

                        $documents[] = $this->documentBuilder->buildForEntity($entity, $className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE);
                    }
                }
            }
        }

        $this->entitiesRelated = [];

        // TODO chunk in batch from config in client bundle

        return $documents;
    }

    private function prepareEntitiesRelated(string $className, mixed $identifierValue, bool $delete): void
    {
        // TODO
        $connection = $this->connectionsBuilder->build()[0] ?? null;

        $entity = $this->persistence->refreshByClassNameId($className, $identifierValue);
        $entitiesRelatedPreDelete = $this->entitiesRelatedBuilder->build($connection, $entity, ChangedEntityEvent::TYPE_DELETE, []);

        $this->entitiesRelated[$className][$identifierValue] = [];
        foreach ($entitiesRelatedPreDelete as $entity) {
            $this->entitiesRelated[$className][$identifierValue][] = [
                'entity' => $entity,
                'delete' => $delete,
            ];
        }
    }
}
