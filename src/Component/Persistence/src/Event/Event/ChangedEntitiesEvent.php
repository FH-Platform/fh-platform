<?php

namespace FHPlatform\Component\Persistence\Event\Event;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

class ChangedEntitiesEvent
{
    /** @param ChangedEntityDTO[] $changedEntities */
    public function __construct(
        private readonly array $changedEntities,
    ) {
    }

    public function getChangedEntities(): array
    {
        return $this->changedEntities;
    }
}
