<?php

namespace FHPlatform\Component\Syncer\Message;

use FHPlatform\Component\SearchEngine\Manager\DataManager;

class EntitiesChangedMessageHandler
{
    public function __construct(
        // private readonly PersistenceInterface $persistence,
        //private readonly DataManager $dataManager,
        // private readonly ConnectionsBuilder $connectionsBuilder,
        // private readonly DocumentBuilder $documentBuilder,
        // private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    ) {
    }

    public function handle(EntitiesChangedMessage $message): void
    {

    }
}
