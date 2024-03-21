<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection;

use FHPlatform\ConfigBundle\Config\Connection\ProviderConnection;

class ProviderConnection_Default2 extends ProviderConnection
{
    public function getName(): string
    {
        return 'default2';
    }

    public function getIndexPrefix(): string
    {
        return 'prefix_default2_';
    }

    public function getClientConfig(): array
    {
        return ['test2' => 'test2'];
    }
}
