<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Syncer\Syncer\EntitySyncer;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SyncEntitiesEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntitySyncer $entitySyncer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SyncEntitiesEvent::class => 'onSyncEntities',
        ];
    }

    public function onSyncEntities(SyncEntitiesEvent $event): void
    {
        $this->entitySyncer->syncChangedEntities($event);
    }
}
