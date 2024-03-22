<?php

namespace FHPlatform\Component\Persistence\Event\EventListener;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class EventListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function onChangedEntities(ChangedEntitiesEvent $event): void
    {
        $this->messageBus->dispatch(new EntitiesChangedMessage($event));
    }
}
