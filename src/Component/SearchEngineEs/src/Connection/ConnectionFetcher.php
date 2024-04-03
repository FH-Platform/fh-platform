<?php

namespace FHPlatform\Component\SearchEngineEs\Connection;

use Elastica\Client;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use Psr\Log\LoggerInterface;

class ConnectionFetcher
{
    public function __construct(
        private readonly LoggerInterface $elasticaLogger
    ) {
    }

    public function fetchClientByConnection(Connection $connection): Client
    {
        $client = new Client($connection->getConfigClient());

        $client->setLogger($this->elasticaLogger);

        return $client;
    }

    public function fetchClientByIndex(Index $index): Client
    {
        $connection = $index->getConnection();

        return $this->fetchClientByConnection($connection);
    }
}
