<?php

namespace App\Es\Config\Provider\Connection;

use FHPlatform\Component\Config\Config\Connection\ProviderConnection;

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

    public function getClientConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'elasticsearch', 'port' => '9200'],
            ],
        ];
    }
}
