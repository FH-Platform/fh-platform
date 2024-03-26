<?php

namespace FHPlatform\Component\Persistence\Message\MessageHandler;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\Builder\IndexBuilder;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
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
        private readonly IndexBuilder $indexBuilder,
    ) {
    }

    public function handle(EntitiesChangedMessage $message): void
    {
        $classNamesIndex = $this->indexBuilder->fetchClassNamesIndex();

        $documents = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getChangedEntities() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifier();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            $entity = $this->persistence->refreshByClassNameId($className, $identifier);

            if (in_array($className, $classNamesIndex)) {
                $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);

                foreach ($indexes as $index) {
                    $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;

                    // TODO return if hash exists

                    if (ChangedEntityDTO::TYPE_DELETE_PRE !== $type) {
                        $documents[$hash] = $this->documentBuilder->build($entity, $className, $identifier, $type);
                    } else {
                        // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
                    }
                }
            }

            if ($entity) {
                $entitiesRelated = $this->entitiesRelatedBuilder->build($entity, $changedFields);
            }
        }

        // TODO chunk in batch from config in client bundle
        $this->dataManager->syncDocuments($documents);
    }
}
