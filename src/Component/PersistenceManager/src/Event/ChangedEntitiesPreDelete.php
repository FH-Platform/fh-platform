<?php

namespace FHPlatform\Component\PersistenceManager\Event;

use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;

class ChangedEntitiesPreDelete
{
    public function __construct(
        private readonly array $changedEntitiesPreDelete,
    ) {
    }

    /** @return ChangedEntityPreDelete[] */
    public function getChangedEntitiesPreDelete(): array
    {
        return $this->changedEntitiesPreDelete;
    }
}
