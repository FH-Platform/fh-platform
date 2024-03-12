<?php

namespace FHPlatform\DataSyncBundle\MessageHandler;

use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ConfigBundle\Fetcher\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Finder\ProviderFinder;
use FHPlatform\DataSyncBundle\Message\ChangedEntitiesMessage;
use FHPlatform\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\UtilBundle\Helper\EntityHelper;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ChangedEntitiesMessageHandler
{
    public function __construct(
        private readonly EntityHelper $entityHelper,
        private readonly DataClient $dataClient,
        private readonly ProviderFinder $providerFinder,
        private readonly EntityFetcher $entityFetcher,
        private readonly IndexFetcher $indexFetcher,
    ) {
    }

    public function __invoke(ChangedEntitiesMessage $message): void
    {
        $entitiesUpsert = $entitiesDelete = [];

        $event = $message->getChangedEntitiesEvent();
        foreach ($event->getEntities() as $changedEntity) {
            /** @var ChangedEntityEvent $changedEntity */
            $className = $changedEntity->getClassName();
            $identifier = $changedEntity->getIdentifier();
            $type = $changedEntity->getType();

            if (!$this->providerFinder->findProviderEntity($className, false)) {
                continue;
            }

            $entity = $this->entityHelper->refreshByClassNameId($className, $identifier);
            if (!$entity) {
                $index = $this->indexFetcher->fetch($className);
                $entitiesDelete[$className.'_'.$identifier] = new Entity(null, $className, $identifier, $index, [], false);
                continue;
            }

            // TODO do upsert by ChangedFields
            $changedFields = $changedEntity->getChangedFields();

            $entitiesUpsert[] = $this->entityFetcher->fetch($entity);
        }

        // TODO chunk in batch from config in client bundle
        $this->dataClient->upsertBatch($entitiesUpsert);
        $this->dataClient->deleteBatch($entitiesDelete);
    }
}
