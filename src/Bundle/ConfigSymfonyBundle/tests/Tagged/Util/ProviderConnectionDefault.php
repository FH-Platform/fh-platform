<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Tagged\Util;

use FHPlatform\ConfigBundle\Config\Connection\ProviderConnection;

class ProviderConnectionDefault extends ProviderConnection
{
    public function getIndexPrefix(): string
    {
        return 'test';
    }

    public function getClientConfig(): array
    {
        return [];
    }
}
