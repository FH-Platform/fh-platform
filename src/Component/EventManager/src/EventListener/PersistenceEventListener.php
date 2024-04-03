<?php

namespace FHPlatform\Component\EventManager\EventListener;

use FHPlatform\Component\EventManager\Manager\EventManager;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Event\FlushEvent;
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
            ChangedEntityEvent::class => 'onChangedEntity',
            FlushEvent::class => 'onFlush',
        ];
    }

    public function onChangedEntity(ChangedEntityEvent $event): void
    {
        $this->eventManager->changedEntityEvent($event);
    }

    public function onFlush(FlushEvent $event): void
    {
        $this->eventManager->flushEvent();
    }
}
