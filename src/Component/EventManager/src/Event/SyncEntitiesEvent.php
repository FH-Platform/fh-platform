<?php

namespace FHPlatform\Component\EventManager\Event;

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
