<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Log;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
