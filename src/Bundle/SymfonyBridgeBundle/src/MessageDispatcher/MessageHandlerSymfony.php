<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher;

use FHPlatform\Component\Persistence\Message\Message\EntitiesChangedMessage;
use FHPlatform\Component\Persistence\Message\MessageHandler\MessageHandler;

class MessageHandlerSymfony
{
    public function __construct(
        private readonly MessageHandler $messageHandler,
    )
    {
    }

    public function __invoke(EntitiesChangedMessage $message): void
    {
        $this->messageHandler->handle($message);
    }
}
