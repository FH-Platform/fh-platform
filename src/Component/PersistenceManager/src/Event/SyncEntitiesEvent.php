<?php

namespace FHPlatform\Component\PersistenceManager\Event;

class SyncEntitiesEvent
{
    public function __construct(
        private readonly array $syncEntityEvents,
    ) {
    }

    /** @return SyncEntityEvent[] */
    public function getSyncEntityEvents(): array
    {
        return $this->syncEntityEvents;
    }
}
