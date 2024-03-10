<?php

namespace FHPlatform\DataSyncBundle\EventListener;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Event\PreDeleteEntityEvent;
use FHPlatform\DataSyncBundle\Message\ChangedEntitiesMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: ChangedEntitiesEvent::class, method: 'onChangedEntities')]
#[AsEventListener(event: PreDeleteEntityEvent::class, method: 'onPreDeleteEntity')]
final class PersistenceBundleListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function onChangedEntities(ChangedEntitiesEvent $event): void
    {
        $this->messageBus->dispatch(new ChangedEntitiesMessage($event));
    }

    public function onPreDeleteEntity(PreDeleteEntityEvent $event): void
    {
        // TODO related entities
    }
}
