<?php

namespace App\FHPlatform\Config\Provider\Connection;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ConnectionLog extends ProviderConnection
{
    public function getName(): string
    {
        return 'log';
    }

    public function getIndexPrefix(): string
    {
        return 'fh_platform_another_dev_';
    }

    public function getClientConfig(): array
    {
        return [
            'servers' => [
                [
                    'host' => 'elasticsearch2',
                    'port' => '9200',
                    'headers' => [
                        'Authorization' => 'Basic ZWxhc3RpYzplbGFzdGlj',
                    ],
                ],
            ],
        ];
    }
}
