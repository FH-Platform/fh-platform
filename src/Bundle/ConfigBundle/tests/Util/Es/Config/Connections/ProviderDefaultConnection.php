<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections;

use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;

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

    public function getElasticaConfig(): array
    {
        return [
            'servers' => [
                ['host' => 'elasticsearch', 'port' => '9201'],
            ],
        ];
    }
}