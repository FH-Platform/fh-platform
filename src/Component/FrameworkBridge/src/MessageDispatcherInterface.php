<?php

namespace FHPlatform\Component\FrameworkBridge;

use FHPlatform\Component\Persistence\Message\EntitiesChangedMessage;

// message(queue) dispatcher interface for each framework
interface MessageDispatcherInterface
{
    public function dispatch(EntitiesChangedMessage $message, bool $sync = false): void;
}
