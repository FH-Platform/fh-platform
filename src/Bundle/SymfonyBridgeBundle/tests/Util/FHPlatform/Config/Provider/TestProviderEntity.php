<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;

class TestProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'Test';
    }
}
