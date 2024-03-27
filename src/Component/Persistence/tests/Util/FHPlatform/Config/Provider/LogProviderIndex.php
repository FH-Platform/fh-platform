<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderIndex;
use FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
