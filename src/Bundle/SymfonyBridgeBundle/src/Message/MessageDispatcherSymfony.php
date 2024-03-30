<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Message;

use FHPlatform\Component\FrameworkBridge\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Message\EntitiesChangedMessage;
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
