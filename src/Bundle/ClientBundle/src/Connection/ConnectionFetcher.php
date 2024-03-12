<?php

namespace FHPlatform\ClientBundle\Connection;

use Elastica\Client;
use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;

class ConnectionFetcher extends Client
{
    public function fetch(Connection $connection)
    {
        return new ElasticaClient($connection);
    }
}
