<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Message;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class MessageDispatcherSymfony
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
