<?php

namespace FHPlatform\Component\Persistence\Syncer;

use FHPlatform\Component\Persistence\Manager\EventManager;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataSyncer
{
    public function __construct(
        private readonly EventManager $eventManager,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function sync(string $className): void
    {
        $identifiers = $this->persistence->getAllIdentifierValues($className);

        // TODO temp index
        $this->eventManager->syncEntitiesManually([$className => $identifiers]);
    }
}
