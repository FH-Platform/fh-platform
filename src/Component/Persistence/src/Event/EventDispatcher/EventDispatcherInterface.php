<?php

namespace FHPlatform\Component\Persistence\Event\EventDispatcher;

use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;

interface EventDispatcherInterface
{
    public function dispatch(ChangedEntitiesEvent $event): void;
}
