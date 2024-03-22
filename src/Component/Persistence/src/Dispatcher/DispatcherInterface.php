<?php

namespace FHPlatform\Component\Persistence\Dispatcher;

use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;

interface DispatcherInterface
{
    public function dispatch(EntitiesChangedMessage $entitiesChangedMessage): void;
}
