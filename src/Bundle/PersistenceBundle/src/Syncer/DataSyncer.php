<?php

namespace FHPlatform\Bundle\PersistenceBundle\Syncer;

use FHPlatform\Bundle\PersistenceBundle\DTO\ChangedEntityDTO;
use FHPlatform\Bundle\PersistenceBundle\Event\EventDispatcher\EventDispatcher;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Helper\DoctrineHelper;

class DataSyncer
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function sync(string $className): void
    {
        $identifiers = $this->doctrineHelper->getAllIds($className);

        // TODO temp index
        $events = [];
        foreach ($identifiers as $identifier) {
            $this->eventDispatcher->addEvent($className, $identifier, ChangedEntityDTO::TYPE_UPDATE, ['id']);
        }

        $this->eventDispatcher->dispatchEvents();
    }
}
