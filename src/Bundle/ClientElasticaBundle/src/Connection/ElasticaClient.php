<?php

namespace FHPlatform\Bundle\ClientElasticaBundle\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use Elastica\Client;

class ElasticaClient extends Client
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection->getConfigClient());
    }
}
