<?php

namespace FHPlatform\Component\Persistence\Event\Event;

use FHPlatform\Component\Persistence\DTO\ChangedEntity;

class ChangedEntitiesEvent
{
    /** @param ChangedEntity[] $changedEntities */
    public function __construct(
        private readonly array $changedEntities,
    ) {
    }

    public function getChangedEntities(): array
    {
        return $this->changedEntities;
    }
}
