<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Entity\Log;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class LogProvider extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
