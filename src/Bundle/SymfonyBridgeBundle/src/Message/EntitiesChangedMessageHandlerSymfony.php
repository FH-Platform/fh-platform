<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Message;

use FHPlatform\Component\Persistence\Message\EntitiesChangedMessage;
use FHPlatform\Component\Persistence\Message\EntitiesChangedMessageHandler;

class EntitiesChangedMessageHandlerSymfony
{
    public function __construct(
        private readonly EntitiesChangedMessageHandler $entitiesChangedMessageHandler,
    ) {
    }

    public function __invoke(EntitiesChangedMessage $message): void
    {
        $this->entitiesChangedMessageHandler->handle($message);
    }
}
