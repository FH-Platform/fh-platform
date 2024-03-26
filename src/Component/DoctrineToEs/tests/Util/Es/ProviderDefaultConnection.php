<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Es;

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
                ['host' => 'elasticsearch', 'port' => '9200'],
            ],
        ];
    }
}
