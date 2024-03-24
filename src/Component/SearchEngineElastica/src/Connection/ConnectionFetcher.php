<?php

namespace FHPlatform\Component\SearchEngineElastica\Connection;

use Elastica\Client;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;

class ConnectionFetcher extends Client
{
    public function fetchByConnection(Connection $connection): ElasticaClient
    {
        return new ElasticaClient($connection);
    }

    public function fetchByIndex(Index $index): ElasticaClient
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
