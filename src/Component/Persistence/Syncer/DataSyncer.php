<?php

namespace FHPlatform\Component\Persistence\Syncer;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcher;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataSyncer
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function sync(string $className): void
    {
        $identifiers = $this->persistence->getAllIds($className);

        // TODO temp index
        $events = [];
        foreach ($identifiers as $identifier) {
            $this->eventDispatcher->addEvent($className, $identifier, ChangedEntityDTO::TYPE_UPDATE, ['id']);
        }

        $this->eventDispatcher->dispatchEvents();
    }
}
