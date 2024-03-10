<?php

namespace FHPlatform\DataSyncBundle\Message;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;

class ChangedEntitiesMessage
{
    public function __construct(
        private readonly ChangedEntitiesEvent $changedEntitiesEvent,
    ) {
    }

    public function getChangedEntitiesEvent(): ChangedEntitiesEvent
    {
        return $this->changedEntitiesEvent;
    }
}
