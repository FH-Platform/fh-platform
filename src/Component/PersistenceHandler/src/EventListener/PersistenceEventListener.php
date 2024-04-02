<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\EventListener;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\PersistenceHandler\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventManager $eventManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ChangedEntity::class => 'onChangedEntity',
            Flush::class => 'onFlush',
        ];
    }

    public function onChangedEntity(ChangedEntity $event): void
    {
        $this->eventManager->eventChangedEntity($event);
    }

    public function onFlush(Flush $event): void
    {
        $this->eventManager->eventFlush($event);
    }
}
