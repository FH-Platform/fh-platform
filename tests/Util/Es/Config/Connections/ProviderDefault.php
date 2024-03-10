<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Connections;

use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;

class ProviderDefault extends ConnectionProvider
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
                ['host' => 'localhost', 'port' => '9201'],
            ],
        ];
    }
}
