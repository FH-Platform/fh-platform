<?php

namespace FHPlatform\ClientBundle\Client;

use Elastica\Client;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;

class ElasticaClient extends Client
{
    public function __construct(private readonly ClientBundleProvider $clientBundleProvider)
    {
        $config = $this->clientBundleProvider->getConnections()[0] ?? null;

        $config = $config ? $config->getElasticaConfig() : [];

        parent::__construct($config);
    }
}
