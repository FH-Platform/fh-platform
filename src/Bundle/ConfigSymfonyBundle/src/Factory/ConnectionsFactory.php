<?php

namespace FHPlatform\ConfigSymfonyBundle\Factory;

use FHPlatform\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\ConfigBundle\Provider\ConnectionsProvider;

class ConnectionsFactory
{
    public static function create(ConnectionsBuilder $connectionsBuilder): ConnectionsProvider
    {
        $connections = $connectionsBuilder->build();

        return new ConnectionsProvider($connections);
    }
}
