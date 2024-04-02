<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;
use FHPlatform\Component\EventManager\Event\ChangedEntities;

class EventDispatcherSymfony implements EventDispatcherInterface
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(ChangedEntities $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
