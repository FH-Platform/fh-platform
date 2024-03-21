<?php

namespace FHPlatform\Bundle\ClientElasticaBundle\Connection;

use Elastica\Client;
use FHPlatform\Bundle\ConfigBundle\DTO\Connection;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

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
