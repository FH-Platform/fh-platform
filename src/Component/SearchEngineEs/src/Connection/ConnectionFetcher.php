<?php

namespace FHPlatform\Component\SearchEngineEs\Connection;

use Elastica\Client;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use Psr\Log\LoggerInterface;

class ConnectionFetcher
{

    public function fetchByConnection(Connection $connection): ElasticaClient
    {
        $client = new ElasticaClient($connection);

        return $client;
    }

    public function fetchByIndex(Index $index): ElasticaClient
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
