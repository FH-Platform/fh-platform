<?php

namespace FHPlatform\Component\Persistence\Tests\Util\Es\Config\Connections;

use FHPlatform\Component\Config\Config\Provider\ProviderConnection;

class ProviderDefaultConnection extends ProviderConnection
{
    public function getName(): string
    {
        return 'default';
    }

    public function getIndexPrefix(): string
    {
        return 'prefix_';
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
