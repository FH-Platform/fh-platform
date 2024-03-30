<?php

namespace FHPlatform\Component\Persistence\Event;

use FHPlatform\Component\FrameworkBridge\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Message\EntitiesChangedMessage;

class ChangedEntitiesEventListener
{
    public function __construct(
        private readonly MessageDispatcherInterface $dispatcher,
    ) {
    }

    public function handle(ChangedEntitiesEvent $event): void
    {
        $this->dispatcher->dispatch(new EntitiesChangedMessage($event));
    }
}
