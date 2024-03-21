<?php

namespace FHPlatform\Bundle\DataSyncBundle\EventListener;

use FHPlatform\Bundle\DataSyncBundle\Message\DoctrineEntitiesChangedMessage;
use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceBundle\Event\PreDeleteEntityEvent;
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
        $this->messageBus->dispatch(new DoctrineEntitiesChangedMessage($event));
    }

    public function onPreDeleteEntity(PreDeleteEntityEvent $event): void
    {
        // TODO related entities
    }
}
