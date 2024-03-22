<?php

namespace FHPlatform\Component\Persistence\MessageDispatcher;

use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;

interface MessageDispatcherInterface
{
    public function dispatch(EntitiesChangedMessage $entitiesChangedMessage): void;
}
