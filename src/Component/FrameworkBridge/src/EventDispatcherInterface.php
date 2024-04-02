<?php

namespace FHPlatform\Component\FrameworkBridge;

use FHPlatform\Component\PersistenceManager\Event\ChangedEntities;

// event dispatcher interface for each framework
interface EventDispatcherInterface
{
    public function dispatch(ChangedEntities $event): void;
}
