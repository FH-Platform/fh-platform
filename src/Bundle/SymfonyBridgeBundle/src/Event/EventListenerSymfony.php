<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Event;

use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEventListener;

class EventListenerSymfony
{
    public function __construct(
        private readonly ChangedEntitiesEventListener $eventListener,
    ) {
    }

    public function handle(ChangedEntitiesEvent $event): void
    {
        $this->eventListener->handle($event);
    }
}
