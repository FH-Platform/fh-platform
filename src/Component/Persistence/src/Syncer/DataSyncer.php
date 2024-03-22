<?php

namespace FHPlatform\Component\Persistence\Syncer;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataSyncer
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly EventHelper          $eventDispatcher,
    ) {
    }

    public function sync(string $className): void
    {
        $identifiers = $this->persistence->getAllIds($className);

        // TODO temp index
        foreach ($identifiers as $identifier) {
            $this->eventDispatcher->addEntity($className, $identifier, ChangedEntityDTO::TYPE_UPDATE, ['id'], false);
        }

        $this->eventDispatcher->dispatch($this->eventDispatcher->getChangedEntitiesDTO());
    }
}
