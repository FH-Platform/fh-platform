<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher;

use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use FHPlatform\Component\Persistence\Message\MessageDispatcher\MessageDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageDispatcherSymfony implements MessageDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(EntitiesChangedMessage $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
