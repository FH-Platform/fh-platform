<?php

namespace FHPlatform\Component\Persistence\Event\EventListener;

use FHPlatform\Component\Persistence\MessageDispatcher\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;

class EventListener
{
    public function __construct(
        private readonly MessageDispatcherInterface $dispatcher,
    ) {
    }

    public function onChangedEntities(ChangedEntitiesEvent $event): void
    {
        $this->dispatcher->dispatch(new EntitiesChangedMessage($event));
    }
}
