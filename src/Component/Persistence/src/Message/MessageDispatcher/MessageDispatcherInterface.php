<?php

namespace FHPlatform\Component\Persistence\Message\MessageDispatcher;

use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;

interface MessageDispatcherInterface
{
    public function dispatch(EntitiesChangedMessage $message): void;
}
