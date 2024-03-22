<?php

namespace FHPlatform\Component\Persistence\Message\Message;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;

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
