<?php

namespace FHPlatform\Component\Persistence\Syncer;

use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Manager\EventManager;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataSyncer
{
    private EventManager $eventHelper;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        private readonly PersistenceInterface $persistence,
    ) {
        $this->eventHelper = new EventManager($eventDispatcher);
    }

    public function sync(string $className): void
    {
        $identifiers = $this->persistence->getAllIdentifierValues($className);

        // TODO temp index
        foreach ($identifiers as $identifier) {
            // TODO remove 'id'
            $this->eventHelper->addEntity($className, $identifier, ChangedEntity::TYPE_UPDATE, ['id']);
        }

        $this->eventHelper->dispatch();
    }
}
