<?php

namespace FHPlatform\Component\Persistence\Event\EventListener;

use FHPlatform\Component\Persistence\Dispatcher\DispatcherInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;

class EventListener
{
    public function __construct(
        private readonly DispatcherInterface $dispatcher,
    ) {
    }

    public function onChangedEntities(ChangedEntitiesEvent $event): void
    {
        $this->dispatcher->dispatch(new EntitiesChangedMessage($event));
    }
}
