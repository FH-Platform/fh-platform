<?php

namespace FHPlatform\DataSyncBundle\Factory;

use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;

class ClientBundleProviderFactory
{
    public static function create(ConnectionsFetcher $connectionsFetcher, IndexesFetcher $indexesFetcher): ClientBundleProvider
    {
        $indexProvider = new ClientBundleProvider();

        $indexProvider->setIndexes($indexesFetcher->fetch());
        $indexProvider->setConnections($connectionsFetcher->fetch());

        return $indexProvider;
    }
}
