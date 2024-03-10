<?php

namespace FHPlatform\DataSyncBundle\MessageHandler;

use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;
use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
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

            // TODO check if class searchable
            try {
                // TODO only once per className
                $entityProvider = $this->providerFinder->findProviderEntity($className);
            } catch (ProviderForClassNameNotExists $e) {
                continue;
            }

            $entity = $this->entityHelper->refreshByClassNameId($className, $identifier);
            if (!$entity) {
                $entitiesDelete[$className.'_'.$identifier] = [
                    'className' => $className,
                    'identifier' => $identifier,
                ];
                continue;
            }

            // TODO do upsert by ChangedFields
            $changedFields = $changedEntity->getChangedFields();

            $entitiesUpsert[$className.'_'.$identifier] = [
                'className' => $className,
                'identifier' => $identifier,
                'data' => $this->entityFetcher->fetch($entity)->getData(),
            ];
        }

        // TODO from config
        $entitiesUpsertBatches = array_chunk($entitiesUpsert, 5);
        $entitiesDeleteBatches = array_chunk($entitiesDelete, 5);

        foreach ($entitiesUpsertBatches as $entitiesUpsert) {
            $this->dataClient->upsertBatch($entitiesUpsert);
        }

        foreach ($entitiesDeleteBatches as $entitiesDelete) {
            $this->dataClient->deleteBatch($entitiesDelete);
        }
    }
}
