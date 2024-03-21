<?php

namespace FHPlatform\Bundle\PersistenceBundle\Syncer;

use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntityEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class DataSyncer
{
    public function __construct(
        private readonly IdentifiersFetcher $identifiersFetcher,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function sync(string $className): void
    {
        $identifiers = $this->identifiersFetcher->fetch($className);

        // TODO temp index
        $events = [];
        foreach ($identifiers as $identifier) {
            $events[] = new ChangedEntityEvent($className, $identifier, ChangedEntityEvent::TYPE_UPDATE, ['id']);
        }

        $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($events));
    }
}
