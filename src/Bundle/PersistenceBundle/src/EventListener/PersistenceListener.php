<?php

namespace FHPlatform\Bundle\PersistenceBundle\EventListener;

use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceBundle\Event\PreDeleteEntityEvent;
use FHPlatform\Bundle\PersistenceBundle\Message\EntitiesChangedMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: ChangedEntitiesEvent::class, method: 'onChangedEntities')]
#[AsEventListener(event: PreDeleteEntityEvent::class, method: 'onPreDeleteEntity')]
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

    public function onPreDeleteEntity(PreDeleteEntityEvent $event): void
    {
        // TODO related entities
    }
}
