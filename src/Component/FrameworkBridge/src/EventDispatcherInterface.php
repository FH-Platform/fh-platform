<?php

namespace FHPlatform\Component\FrameworkBridge;

use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;

// event dispatcher interface for each framework
interface EventDispatcherInterface
{
    public function dispatch(ChangedEntitiesEvent $event): void;
}
