<?php

namespace FHPlatform\Component\Persistence\Message\MessageHandler;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Manager\DataManager;

class MessageHandler
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly DataManager $dataManager,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly DocumentBuilder $documentBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    ) {
    }

    public function handle(EntitiesChangedMessage $message): void
    {
        $documents = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getChangedEntities() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifier();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            $entity = $this->persistence->refreshByClassNameId($className, $identifier);

            $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);
            foreach ($indexes as $index) {
                $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;

                // TODO return if hash exists

                if (ChangedEntity::TYPE_DELETE_PRE !== $type) {
                    $documents[$hash] = $this->documentBuilder->buildForEntity($entity, $className, $identifier, $type);
                } else {
                    // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
                }
            }

            if ($entity) {
                $connections = $this->connectionsBuilder->build();

                foreach ($connections as $connection) {
                    $entitiesRelated = $this->entitiesRelatedBuilder->build($connection, $entity, $changedFields);

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
}
