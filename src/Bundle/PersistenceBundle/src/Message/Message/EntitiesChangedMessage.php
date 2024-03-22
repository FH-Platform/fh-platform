<?php

namespace FHPlatform\Bundle\PersistenceBundle\Message\Message;

use FHPlatform\Bundle\PersistenceBundle\Event\Event\ChangedEntitiesEvent;

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
