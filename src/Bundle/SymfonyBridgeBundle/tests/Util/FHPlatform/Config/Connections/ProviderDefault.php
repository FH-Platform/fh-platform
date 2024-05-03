<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Connections;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ProviderDefault extends ProviderConnection
{
    public function getConnectionName(): string
    {
        return 'default';
    }

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
