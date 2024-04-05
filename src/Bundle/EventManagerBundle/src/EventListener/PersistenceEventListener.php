<?php

namespace FHPlatform\Bundle\EventManagerBundle\EventListener;

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
            ChangedEntityEvent::class => 'onChangedEntityEvent',
            FlushEvent::class => 'onFlushEvent',
        ];
    }

    public function onChangedEntityEvent(ChangedEntityEvent $event): void
    {
        $this->eventManager->changedEntityEvent($event);
    }

    public function onFlushEvent(FlushEvent $event): void
    {
        $this->eventManager->flushEvent();
    }
}
