<?php

namespace FHPlatform\Component\PersistenceHandler\Event;

class ChangedEntities
{
    public function __construct(
        private readonly array $changedEntities,
    ) {
    }

    public function getChangedEntities(): array
    {
        return $this->changedEntities;
    }
}
