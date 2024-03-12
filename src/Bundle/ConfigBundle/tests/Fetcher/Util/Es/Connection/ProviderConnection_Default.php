<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection;

use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;

class ProviderConnection_Default extends ProviderConnection
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
