<?php

namespace FHPlatform\Component\FrameworkBridge;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;

// event dispatcher interface for each framework
interface EventDispatcherInterface
{
    public function dispatch(SyncEntitiesEvent $event): void;
}
