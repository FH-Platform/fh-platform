<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Dispatcher;

use FHPlatform\Component\Persistence\Dispatcher\DispatcherInterface;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class Dispatcher implements DispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(EntitiesChangedMessage $entitiesChangedMessage): void
    {
        $this->messageBus->dispatch($entitiesChangedMessage);
    }
}
