<?php

namespace FHPlatform\Component\Persistence\Message;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;

class SyncDocumentsMessage
{
    public function __construct(
        private readonly SyncEntitiesEvent $changedEntitiesEvent,
    ) {
    }

    public function getChangedEntitiesEvent(): SyncEntitiesEvent
    {
        return $this->changedEntitiesEvent;
    }
}
