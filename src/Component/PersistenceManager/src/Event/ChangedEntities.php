<?php

namespace FHPlatform\Component\PersistenceManager\Event;

use FHPlatform\Component\Persistence\Event\ChangedEntity;

class ChangedEntities
{
    public function __construct(
        private readonly array $changedEntities,
    ) {
    }

    /** @return ChangedEntity[] */
    public function getChangedEntities(): array
    {
        return $this->changedEntities;
    }
}
