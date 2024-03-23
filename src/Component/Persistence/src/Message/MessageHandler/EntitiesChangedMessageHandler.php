<?php

namespace FHPlatform\Component\Persistence\Message\MessageHandler;

use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\Builder\EntityBuilder;
use FHPlatform\Component\Config\Builder\IndexBuilder;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class EntitiesChangedMessageHandler
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly DataClient $dataClient,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly EntityBuilder $entityFetcher,
        private readonly EntitiesRelatedBuilder $entityRelatedFetcher,
        private readonly IndexBuilder $indexFetcher,
    ) {
    }

    public function __invoke(EntitiesChangedMessage $message): void
    {
        $classNamesIndex = $this->indexFetcher->fetchClassNamesIndex();
        $classNamesRelated = $this->entityRelatedFetcher->fetchClassNamesRelated();

        $entities = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getChangedEntities() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifier();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            $entity = $this->persistence->refreshByClassNameId($className, $identifier);

            if (in_array($className, $classNamesIndex)) {
                $this->prepareUpdates($entity, $className, $identifier, $type, $entities);
            }

            if (in_array($className, $classNamesRelated) and $entity) {
                $entitiesRelated = $this->entityRelatedFetcher->build($entity);
                // TODO
            }
        }

        // TODO chunk in batch from config in client bundle
        $this->dataClient->syncEntities($entities);
    }

    private function prepareUpdates(mixed $entity, string $className, mixed $identifier, string $type, array &$entities): void
    {
        // TODO cache
        $indexes = $this->connectionsBuilder->fetchIndexesByClassName($className);

        foreach ($indexes as $index) {
            $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;

            // TODO return if hash exists

            if (ChangedEntityDTO::TYPE_DELETE_PRE !== $type) {
                $entities[$hash] = $this->entityFetcher->build($entity, $className, $identifier, $type);
            } else {
                // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
            }
        }
    }
}
