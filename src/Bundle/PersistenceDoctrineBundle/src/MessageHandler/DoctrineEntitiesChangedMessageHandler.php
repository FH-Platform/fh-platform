<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\MessageHandler;

use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Message\DoctrineEntitiesChangedMessage;
use FHPlatform\Bundle\UtilBundle\Helper\EntityHelper;
use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\Builder\EntityBuilder;
use FHPlatform\Component\Config\Builder\IndexBuilder;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DoctrineEntitiesChangedMessageHandler
{
    public function __construct(
        private readonly EntityHelper $entityHelper,
        private readonly DataClient $dataClient,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly EntityBuilder $entityFetcher,
        private readonly EntitiesRelatedBuilder $entityRelatedFetcher,
        private readonly IndexBuilder $indexFetcher,
    ) {
    }

    public function __invoke(DoctrineEntitiesChangedMessage $message): void
    {
        $classNamesIndex = $this->indexFetcher->fetchClassNamesIndex();
        $classNamesRelated = $this->entityRelatedFetcher->fetchClassNamesRelated();

        $entities = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getEvents() as $event) {
            // TODO check if reletable or indexable, fetch entity classNames array and check

            $className = $event->getClassName();
            $identifier = $event->getIdentifier();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            $entity = $this->entityHelper->refreshByClassNameId($className, $identifier);

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

            if (ChangedEntityEvent::TYPE_DELETE === $type) {
                $entities[$hash] = $this->entityFetcher->buildForDelete($className, $identifier);
            } elseif (in_array($type, [ChangedEntityEvent::TYPE_UPDATE, ChangedEntityEvent::TYPE_CREATE])) {
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
