<?php

namespace App\FHPLatform\Config\Provider\Connection;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ConnectionDefault extends ProviderConnection
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
                [
                    'host' => 'elasticsearch',
                    'port' => '9200',
                    'headers' => [
                        'Authorization' => 'Basic ZWxhc3RpYzplbGFzdGlj',
                    ],
                ],
            ],
        ];
    }
}
