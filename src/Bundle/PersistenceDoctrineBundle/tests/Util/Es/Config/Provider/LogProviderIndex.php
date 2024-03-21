<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Es\Config\Log;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
