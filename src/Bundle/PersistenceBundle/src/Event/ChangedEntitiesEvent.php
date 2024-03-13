<?php

namespace FHPlatform\PersistenceBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ChangedEntitiesEvent extends Event
{
    /** @var ChangedEntityEvent[] */
    public function __construct(
        private readonly array $events,
    ) {
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
