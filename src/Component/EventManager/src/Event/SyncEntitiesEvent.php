<?php

namespace FHPlatform\Component\EventManager\Event;

use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;

class SyncEntitiesEvent
{
    public function __construct(
        private readonly array $changedEntityEvents,
    ) {
    }

    /** @return ChangedEntityEvent[] */
    public function getChangedEntityEvents(): array
    {
        return $this->changedEntityEvents;
    }
}
