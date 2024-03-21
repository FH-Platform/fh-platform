<?php

namespace FHPlatform\Bundle\PersistenceBundle\EventListener;

use FHPlatform\Bundle\PersistenceBundle\Message\DoctrineEntitiesChangedMessage;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Event\PreDeleteEntityEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: ChangedEntitiesEvent::class, method: 'onChangedEntities')]
#[AsEventListener(event: PreDeleteEntityEvent::class, method: 'onPreDeleteEntity')]
class PersistenceDoctrineBundleListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function onChangedEntities(ChangedEntitiesEvent $event): void
    {
        $this->messageBus->dispatch(new DoctrineEntitiesChangedMessage($event));
    }

    public function onPreDeleteEntity(PreDeleteEntityEvent $event): void
    {
        // TODO related entities
    }
}