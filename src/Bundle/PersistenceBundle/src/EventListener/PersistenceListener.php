<?php

namespace FHPlatform\Bundle\PersistenceBundle\EventListener;

use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceBundle\Message\EntitiesChangedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class PersistenceListener
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
