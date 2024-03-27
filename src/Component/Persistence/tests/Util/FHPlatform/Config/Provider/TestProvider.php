<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;

class TestProvider extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'Test';
    }
}
