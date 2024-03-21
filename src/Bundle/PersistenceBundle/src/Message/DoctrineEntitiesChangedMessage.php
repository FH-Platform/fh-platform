<?php

namespace FHPlatform\Bundle\PersistenceBundle\Message;

use FHPlatform\Bundle\PersistenceDoctrineBundle\Event\ChangedEntitiesEvent;

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
