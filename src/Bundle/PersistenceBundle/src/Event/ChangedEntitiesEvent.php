<?php

namespace FHPlatform\Bundle\PersistenceBundle\Event;

use FHPlatform\Bundle\PersistenceBundle\DTO\ChangedEntityDTO;

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
