<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher;

use FHPlatform\Component\Persistence\MessageDispatcher\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageDispatcher implements MessageDispatcherInterface
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
