<?php

namespace FHPlatform\Component\SearchEngineEs\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use Psr\Log\LoggerInterface;

class ConnectionFetcher
{
    public function __construct(
        private readonly LoggerInterface $elastica
    ) {
    }

    public function fetchByConnection(Connection $connection): ElasticaClient
    {
        $client = new ElasticaClient($connection);

        $client->setLogger($this->elastica);

        return $client;
    }

    public function fetchByIndex(Index $index): ElasticaClient
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
