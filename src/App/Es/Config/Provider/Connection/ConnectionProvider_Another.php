<?php

namespace App\Es\Config\Provider\Connection;

use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;

class ConnectionProvider_Another extends ConnectionProvider
{
    public function getName(): string
    {
        return 'another';
    }

    public function getIndexPrefix(): string
    {
        return 'fh_platform_another_dev_';
    }

    public function getElasticaConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'elasticsearch2', 'port' => '9200'],
            ],
        ];
    }
}
