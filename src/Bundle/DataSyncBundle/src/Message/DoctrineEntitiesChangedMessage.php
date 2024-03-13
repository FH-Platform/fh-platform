<?php

namespace FHPlatform\DataSyncBundle\Message;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;

class DoctrineEntitiesChangedMessage
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
