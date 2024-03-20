<?php

namespace FHPlatform\DataSyncBundle\MessageHandler;

use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\DoctrineClassesNamesIndexFetcher;
use FHPlatform\ConfigBundle\Fetcher\DoctrineClassesNamesRelatedFetcher;
use FHPlatform\ConfigBundle\Fetcher\Entity\EntityFetcher;
use FHPlatform\ConfigBundle\Fetcher\Entity\EntityRelatedFetcher;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\DataSyncBundle\Message\DoctrineEntitiesChangedMessage;
use FHPlatform\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\UtilBundle\Helper\EntityHelper;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DoctrineEntitiesChangedMessageHandler
{
    public function __construct(
        private readonly EntityHelper $entityHelper,
        private readonly DataClient $dataClient,
        private readonly EntityFetcher $entityFetcher,
        private readonly IndexFetcher $indexFetcher,
        private readonly EntityRelatedFetcher $entityRelatedFetcher,
        private readonly DoctrineClassesNamesIndexFetcher $doctrineClassesNamesIndexFetcher,
        private readonly DoctrineClassesNamesRelatedFetcher $doctrineClassesNamesRelatedFetcher,
    ) {
    }

    public function __invoke(DoctrineEntitiesChangedMessage $message): void
    {
        $classNamesIndex = $this->doctrineClassesNamesIndexFetcher->fetch();
        $classNamesRelated = $this->doctrineClassesNamesRelatedFetcher->fetch();

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
                $entitiesRelated = $this->entityRelatedFetcher->fetch($entity);
                // TODO
            }
        }

        // TODO chunk in batch from config in client bundle
        $this->dataClient->syncEntities($entities);
    }

    private function prepareUpdates(mixed $entity, string $className, mixed $identifier, string $type, array &$entities): void
    {
        // TODO cache
        $indexes = $this->indexFetcher->fetchIndexesByClassName($className);

        foreach ($indexes as $index) {
            $hash = $index->getConnection()->getName().'_'.$index->getName().'_'.$className.'_'.$identifier;

            // TODO return if hash exists

            if (ChangedEntityEvent::TYPE_DELETE === $type) {
                $entities[$hash] = $this->entityFetcher->fetchDelete($className, $identifier);
            } elseif (in_array($type, [ChangedEntityEvent::TYPE_UPDATE, ChangedEntityEvent::TYPE_CREATE])) {
                if (!$entity) {
                    $entities[$hash] = $this->entityFetcher->fetchDelete($className, $identifier);
                } else {
                    $entities[$hash] = $this->entityFetcher->fetchUpsert($entity);
                }
            }

            // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
        }
    }
}
