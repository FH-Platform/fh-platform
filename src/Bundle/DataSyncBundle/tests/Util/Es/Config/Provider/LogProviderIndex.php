<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\Bundle\ConfigBundle\Config\Provider\ProviderIndex;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
