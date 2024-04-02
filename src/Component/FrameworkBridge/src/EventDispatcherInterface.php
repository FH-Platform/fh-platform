<?php

namespace FHPlatform\Component\FrameworkBridge;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\PersistenceHandler\Event\ChangedEntities;

// event dispatcher interface for each framework
interface EventDispatcherInterface
{
    public function dispatch(ChangedEntities $event): void;
}
