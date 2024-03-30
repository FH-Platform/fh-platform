<?php

namespace FHPlatform\Component\Persistence\Message;

use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;

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
