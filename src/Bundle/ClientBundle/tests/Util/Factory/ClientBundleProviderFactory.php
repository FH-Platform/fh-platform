<?php

namespace FHPlatform\ClientBundle\Tests\Util\Factory;

use FHPlatform\ClientBundle\Provider\ClientBundleProvider;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;

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
