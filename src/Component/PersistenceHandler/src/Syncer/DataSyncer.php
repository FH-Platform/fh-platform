<?php

namespace FHPlatform\Component\PersistenceHandler\PersistenceHandler;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceHandler\Manager\EventManager;

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
