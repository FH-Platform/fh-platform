<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceManager\Event\ChangedEntities;
use FHPlatform\Component\PersistenceManager\Event\ChangedEntitiesPreDelete;
use FHPlatform\Component\PersistenceManager\Manager\EventManager;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceManagerEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly DataManager $dataManager,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly DocumentBuilder $documentBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ChangedEntities::class => 'onChangedEntities',
            ChangedEntitiesPreDelete::class => 'onChangedEntitiesPreDelete',
        ];
    }

    public function onChangedEntities(ChangedEntities $event): void
    {
        $documents = [];

        // $event = $message->getChangedEntitiesEvent();
        // TODO
        foreach ($event->getChangedEntities() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifierValue();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            $entity = $this->persistence->refreshByClassNameId($className, $identifier);

            $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
            foreach ($indexes as $index) {
                $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;
            }

            if ($entity) {
                $connections = $this->connectionsBuilder->build();

                foreach ($connections as $connection) {
                    $entitiesRelated = $this->entitiesRelatedBuilder->build($connection, $entity, $type, $changedFields);

                    foreach ($entitiesRelated as $entityRelated) {
                        // TODO separate

                        $className = $this->persistence->getRealClassName($entityRelated::class);
                        $identifier = $this->persistence->getIdentifierValue($entityRelated);

                        $documents[] = $this->documentBuilder->buildForEntity($entityRelated, $className, $identifier, ChangedEntity::TYPE_UPDATE);
                    }
                }
            }
        }

        // TODO chunk in batch from config in client bundle
        $this->dataManager->syncDocuments($documents);
    }

    public function onChangedEntitiesPreDelete(ChangedEntitiesPreDelete $event): void
    {
        foreach ($event->getChangedEntitiesPreDelete() as $event){

        }
    }
}
