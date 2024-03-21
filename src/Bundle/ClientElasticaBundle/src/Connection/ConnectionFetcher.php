<?php

namespace FHPlatform\Bundle\ClientElasticaBundle\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use Elastica\Client;

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
