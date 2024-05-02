<?php

namespace FHPlatform\Bundle\PersistenceBundle\EventListener;

use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Event\FlushEvent;
use FHPlatform\Component\PersistenceManager\Manager\PersistenceManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly PersistenceManager $persistenceManager
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
        $this->persistenceManager->changedEntityEvent($event);
    }

    public function onFlushEvent(FlushEvent $event): void
    {
        $this->persistenceManager->flushEvent();
    }
}
