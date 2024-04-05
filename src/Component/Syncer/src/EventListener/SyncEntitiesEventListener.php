<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
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
            ChangedEntityEvent::class => 'onChangedEntityEvent',
        ];
    }

    public function onSyncEntities(SyncEntitiesEvent $event): void
    {
        $this->entitySyncer->syncEntitiesEvent($event);
    }

    public function onChangedEntityEvent(ChangedEntityEvent $event): void
    {
        $this->entitySyncer->changedEntityEvent($event);
    }
}
