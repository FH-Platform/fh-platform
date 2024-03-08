<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection;

use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;

class ConnectionProviderDefault extends ConnectionProvider
{
    public function getIndexPrefix(): string
    {
        return 'prefix_default_';
    }

    public function getElasticaConfig(): array
    {
        return ['test' => 'test'];
    }
}
