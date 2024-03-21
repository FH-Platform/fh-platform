<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Connection;

use FHPlatform\ConfigBundle\Config\Connection\ProviderConnection;

class ProviderConnection_Default extends ProviderConnection
{
    public function getIndexPrefix(): string
    {
        return 'prefix_default_';
    }

    public function getClientConfig(): array
    {
        return ['test' => 'test'];
    }
}
