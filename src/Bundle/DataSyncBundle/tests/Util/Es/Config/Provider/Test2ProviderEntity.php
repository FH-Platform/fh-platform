<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;

class Test2ProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'Test2';
    }
}
