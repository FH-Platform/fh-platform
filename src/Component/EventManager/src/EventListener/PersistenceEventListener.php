<?php

namespace FHPlatform\Component\EventManager\EventListener;

use FHPlatform\Component\EventManager\Manager\EventManager;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventManager $eventManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ChangedEntity::class => 'onChangedEntity',
            ChangedEntityPreDelete::class => 'onChangedEntityPreDelete',
            Flush::class => 'onFlush',
        ];
    }

    public function onChangedEntity(ChangedEntity $event): void
    {
        $this->eventManager->changedEntityEvent($event);
    }

    public function onChangedEntityPreDelete(ChangedEntityPreDelete $event): void
    {
        // nothing to do
    }

    public function onFlush(Flush $event): void
    {
        $this->eventManager->flushEvent();
    }
}
