<?php

namespace FHPlatform\ClientBundle\Provider\Elastica\Connection;

use Elastica\Client;
use FHPlatform\ConfigBundle\DTO\Connection;

class ConnectionFetcher extends Client
{
    public function fetch(Connection $connection): ElasticaClient
    {
        return new ElasticaClient($connection);
    }
}
