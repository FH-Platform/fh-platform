<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SyncEntitiesEventListener implements EventSubscriberInterface
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

    public static function getSubscribedEvents(): array
    {
        return [
            SyncEntitiesEvent::class => 'onChangedEntities',
        ];
    }

    public function onChangedEntities(SyncEntitiesEvent $event): void
    {
        $documents = [];

        foreach ($event->getChangedEntityEvents() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifierValue();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            if (ChangedEntityEvent::TYPE_DELETE_PRE === $event->getType()) {
                $connection = $this->connectionsBuilder->build()[0] ?? null;

                if ($connection) {
                    $entity = $this->persistence->refreshByClassNameId($event->getClassName(), $event->getIdentifierValue());

                    $entitiesRelatedPreDelete = $this->entitiesRelatedBuilder->build($connection, $entity, ChangedEntityEvent::TYPE_DELETE, []);

                    $this->entitiesRelatedPreDelete = array_merge($this->entitiesRelatedPreDelete, $entitiesRelatedPreDelete);
                }

                return;
            }

            $entity = $this->persistence->refreshByClassNameId($className, $identifier);

            $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
            foreach ($indexes as $index) {
                $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;

                // TODO return if hash exists
                $documents[$hash] = $this->documentBuilder->buildForEntity($entity, $className, $identifier, $type);
            }

            $documents = array_merge($documents, $this->buildForRelatedEntities($entity, $type, $changedFields));
        }

        foreach ($this->entitiesRelatedPreDelete as $entityRelatedPreDelete) {
            // TODO separate

            $className = $this->persistence->getRealClassName($entityRelatedPreDelete::class);
            $identifier = $this->persistence->getIdentifierValue($entityRelatedPreDelete);

            $documents[] = $this->documentBuilder->buildForEntity($entityRelatedPreDelete, $className, $identifier, ChangedEntityEvent::TYPE_UPDATE);
        }

        $this->entitiesRelatedPreDelete = [];

        // TODO chunk in batch from config in client bundle

        $this->dataManager->syncDocuments($documents);
    }

    private function buildForRelatedEntities(mixed $entity, string $type, array $changedFields): array
    {
        $documents = [];
        if ($entity) {
            $connections = $this->connectionsBuilder->build();

            foreach ($connections as $connection) {
                $entitiesRelated = $this->entitiesRelatedBuilder->build($connection, $entity, $type, $changedFields);
                foreach ($entitiesRelated as $entityRelated) {
                    $className = $this->persistence->getRealClassName($entityRelated::class);
                    $identifier = $this->persistence->getIdentifierValue($entityRelated);

                    $documents[] = $this->documentBuilder->buildForEntity($entityRelated, $className, $identifier, ChangedEntityEvent::TYPE_UPDATE);
                }
            }
        }

        return $documents;
    }
}