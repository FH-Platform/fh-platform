<?php

namespace FHPlatform\ConfigBundle\Tests\Tagged\Util;

use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;

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
