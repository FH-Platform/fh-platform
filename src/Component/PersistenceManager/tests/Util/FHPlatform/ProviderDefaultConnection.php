<?php

namespace FHPlatform\Component\PersistenceManager\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ProviderDefaultConnection extends ProviderConnection
{
    public function getIndexPrefix(): string
    {
        return 'prefix_';
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
