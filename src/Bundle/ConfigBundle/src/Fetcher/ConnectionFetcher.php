<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;

class ConnectionFetcher
{
    public function __construct(
        private readonly ConnectionsFetcher $connectionsFetcher,
    ) {
    }

    public function fetch($name = 'default'): Connection
    {
        foreach ($this->connectionsFetcher->fetch() as $connection) {
            if ($connection->getName() === $name) {
                return $connection;
            }
        }

        // TODO
        throw new \Exception('Connection not exist.');
    }
}
