<?php

namespace FHPlatform\Bundle\DataSyncBundle\Message;

use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntitiesEvent;

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
