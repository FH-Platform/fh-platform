<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;

class TestProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'Test';
    }
}
