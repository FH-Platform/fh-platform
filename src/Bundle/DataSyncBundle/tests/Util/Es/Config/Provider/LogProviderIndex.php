<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderIndex;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
