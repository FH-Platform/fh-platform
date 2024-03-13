<?php

namespace FHPlatform\ClientBundle\Connection;

use Elastica\Client;
use FHPlatform\ConfigBundle\DTO\Connection;

class ElasticaClient extends Client
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection->getClientConfig());
    }
}
