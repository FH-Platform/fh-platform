<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\EventDispatcher;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
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
