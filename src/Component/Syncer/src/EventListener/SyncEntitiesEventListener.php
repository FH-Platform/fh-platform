<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\PersistenceManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Syncer\Syncer\EntitySyncer;
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
        $this->entitySyncer->syncEntitiesEvent($event);
    }
}
