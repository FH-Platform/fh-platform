<?php

namespace FHPlatform\Component\SearchEngineEs\Connection;

use Elastica\Client;
use FHPlatform\Component\Config\DTO\Connection;

class ElasticaClient extends Client
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection->getConfigClient());
    }
}
