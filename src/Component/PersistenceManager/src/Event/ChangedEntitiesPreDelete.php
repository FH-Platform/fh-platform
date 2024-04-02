<?php

namespace FHPlatform\Component\PersistenceManager\Event;

class ChangedEntitiesPreDelete
{
    public function __construct(
        private readonly array $changedEntitiesPreDelete,
    ) {
    }

    public function getChangedEntitiesPreDelete(): array
    {
        return $this->changedEntitiesPreDelete;
    }
}
