<?php

namespace FHPlatform\Bundle\PersistenceBundle\Message\MessageHandler;

use FHPlatform\Bundle\PersistenceBundle\DTO\ChangedEntityDTO;
use FHPlatform\Bundle\PersistenceBundle\Message\Message\EntitiesChangedMessage;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Persistence\PersistenceDoctrine;
use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\Builder\EntityBuilder;
use FHPlatform\Component\Config\Builder\IndexBuilder;

class EntitiesChangedMessageHandler
{
    public function __construct(
        private readonly PersistenceDoctrine $persistenceDoctrine,
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

            $entity = $this->persistenceDoctrine->refreshByClassNameId($className, $identifier);

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

            if (ChangedEntityDTO::TYPE_DELETE === $type) {
                $entities[$hash] = $this->entityFetcher->buildForDelete($className, $identifier);
            } elseif (in_array($type, [ChangedEntityDTO::TYPE_UPDATE, ChangedEntityDTO::TYPE_CREATE])) {
                if (!$entity) {
                    $entities[$hash] = $this->entityFetcher->buildForDelete($className, $identifier);
                } else {
                    $entities[$hash] = $this->entityFetcher->buildForUpsert($entity);
                }
            }

            // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
        }
    }
}
