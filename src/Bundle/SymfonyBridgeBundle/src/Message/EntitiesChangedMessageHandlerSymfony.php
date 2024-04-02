<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Message;

use FHPlatform\Component\Syncer\Message\EntitiesChangedMessage;
use FHPlatform\Component\Syncer\Message\EntitiesChangedMessageHandler;

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
