<?php

namespace App\Es\Config\Provider\Connection;

use FHPlatform\Bundle\ConfigBundle\Config\Connection\ProviderConnection;

class Provider_Connection_Another extends ProviderConnection
{
    public function getName(): string
    {
        return 'another';
    }

    public function getIndexPrefix(): string
    {
        return 'fh_platform_another_dev_';
    }

    public function getClientConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'elasticsearch2', 'port' => '9200'],
            ],
        ];
    }
}
