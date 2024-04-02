<?php

namespace FHPlatform\Component\Persistence\Message;

use FHPlatform\Component\PersistenceHandler\Event\ChangedEntities;

class SyncDocumentsMessage
{
    public function __construct(
        private readonly ChangedEntities $changedEntitiesEvent,
    ) {
    }

    public function getChangedEntitiesEvent(): ChangedEntities
    {
        return $this->changedEntitiesEvent;
    }
}
