<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ProviderDefaultConnection extends ProviderConnection
{
    public function getConnectionIndexPrefix(): string
    {
        return 'prefix_';
    }

    public function getConnectionClientConfig(): array
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
