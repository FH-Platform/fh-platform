<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ChangedEntitiesEvent extends Event
{
    /** @param ChangedEntityEvent[] $events */
    public function __construct(
        private readonly array $events,
    ) {
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
