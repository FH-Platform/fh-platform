<?php

namespace FHPlatform\DataSyncBundle\MessageHandler;

use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\Entity\EntityFetcher;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Finder\ProviderFinder;
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
        private readonly ProviderFinder $providerFinder,
        private readonly EntityFetcher $entityFetcher,
        private readonly IndexFetcher $indexFetcher,
    ) {
    }

    public function __invoke(DoctrineEntitiesChangedMessage $message): void
    {
        $entitiesUpsert = $entitiesDelete = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getEvents() as $event) {
            // TODO check if reletable or indexable

            $className = $event->getClassName();
            $identifier = $event->getIdentifier();
            $type = $event->getType();
            $changedFields = $event->getChangedFields();  // TODO do upsert by ChangedFields

            // TODO cache
            $indexes = $this->indexFetcher->fetchIndexesByClassName($className);

            foreach ($indexes as $index) {
                if (ChangedEntityEvent::TYPE_DELETE === $type) {
                    $entitiesDelete[$className.'_'.$identifier] = new Entity($index, $identifier, [], false);
                } elseif (in_array($type, [ChangedEntityEvent::TYPE_UPDATE, ChangedEntityEvent::TYPE_CREATE])) {
                    $entity = $this->entityHelper->refreshByClassNameId($className, $identifier);
                    if (!$entity) {
                        $entitiesDelete[$className.'_'.$identifier] = new Entity($index, $identifier, [], false);
                    } else {
                        $entitiesUpsert[] = $this->entityFetcher->fetch($entity);
                    }
                }

                // TODO -> ChangedEntityEvent::TYPE_DELETE_PRE
            }
        }

        // TODO chunk in batch from config in client bundle
        $this->dataClient->upsertBatch($entitiesUpsert);
        $this->dataClient->deleteBatch($entitiesDelete);
    }
}
