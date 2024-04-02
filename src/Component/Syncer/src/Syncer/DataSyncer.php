<?php

namespace FHPlatform\Component\PersistenceManager\PersistenceManager;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceManager\Manager\EventManager;

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
