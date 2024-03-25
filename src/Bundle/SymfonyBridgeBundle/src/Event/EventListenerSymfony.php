<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventListener\EventListener;

class EventListenerSymfony
{
    public function __construct(
        private readonly EventListener $eventListener,
    ) {
    }

    public function handle(ChangedEntitiesEvent $event): void
    {
        $this->eventListener->handle($event);
    }
}
