<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;

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
