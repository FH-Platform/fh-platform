<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;

class EventDispatcherSymfony implements EventDispatcherInterface
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(SyncEntitiesEvent $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
