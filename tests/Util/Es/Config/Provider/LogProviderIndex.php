<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderIndex;
use FHPlatform\ClientBundle\Tests\Util\Es\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
