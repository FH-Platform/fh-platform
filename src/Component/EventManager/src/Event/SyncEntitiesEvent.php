<?php

namespace FHPlatform\Component\EventManager\Event;

use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;

class SyncEntitiesEvent
{
    public function __construct(
        private readonly array $changedEntities,
    ) {
    }

    /** @return ChangedEntityEvent[] */
    public function getChangedEntities(): array
    {
        return $this->changedEntities;
    }
}
