<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Message;

use FHPlatform\Component\FrameworkBridge\MessageDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class MessageDispatcherSymfony implements MessageDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch($message, bool $sync = false): void
    {
        $config = [];
        if ($sync) {
            $config = [new TransportNamesStamp('sync')];
        }

        $this->messageBus->dispatch($message, $config);
    }
}
