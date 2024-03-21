<?php

namespace FHPlatform\Bundle\PersistenceBundle\Message;

use FHPlatform\Bundle\PersistenceDoctrineBundle\Event\ChangedEntitiesEvent;

class EntitiesChangedMessage
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
