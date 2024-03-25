<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;

class EventDispatcherSymfony implements EventDispatcherInterface
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(ChangedEntitiesEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
