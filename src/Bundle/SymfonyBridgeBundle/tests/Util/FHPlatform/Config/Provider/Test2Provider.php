<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;

class Test2Provider extends ProviderEntity
{
    public function getIndexClassName(): string
    {
        return 'Test2';
    }
}
