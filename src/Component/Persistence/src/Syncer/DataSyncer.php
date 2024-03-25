<?php

namespace FHPlatform\Component\Persistence\Syncer;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataSyncer
{
    private EventHelper $eventHelper;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        private readonly PersistenceInterface $persistence,
    ) {
        $this->eventHelper = new EventHelper($eventDispatcher);
    }

    public function sync(string $className): void
    {
        $identifiers = $this->persistence->getAllIds($className);

        // TODO temp index
        foreach ($identifiers as $identifier) {
            $this->eventHelper->addEntity($className, $identifier, ChangedEntityDTO::TYPE_UPDATE, ['id'], false);
        }

        $this->eventHelper->dispatch($this->eventHelper->getChangedEntitiesDTO());
    }
}
