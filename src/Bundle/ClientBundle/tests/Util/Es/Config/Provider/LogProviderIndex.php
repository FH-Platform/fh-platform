<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ClientBundle\Tests\Util\Es\Config\Log;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderIndex;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
