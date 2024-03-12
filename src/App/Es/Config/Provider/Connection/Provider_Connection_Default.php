<?php

namespace App\Es\Config\Provider\Connection;

use FHPlatform\ConfigBundle\TagProvider\Connection\ProviderConnection;

class Provider_Connection_Default extends ProviderConnection
{
    public function getName(): string
    {
        return 'default';
    }

    public function getIndexPrefix(): string
    {
        return 'fh_platform_dev_';
    }

    public function getElasticaConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'elasticsearch', 'port' => '9200'],
            ],
        ];
    }
}
