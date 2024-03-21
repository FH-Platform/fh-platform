<?php

namespace FHPlatform\Bundle\ClientElasticaBundle\Connection;

use Elastica\Client;
use FHPlatform\Bundle\ConfigBundle\DTO\Connection;

class ElasticaClient extends Client
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection->getConfigClient());
    }
}
