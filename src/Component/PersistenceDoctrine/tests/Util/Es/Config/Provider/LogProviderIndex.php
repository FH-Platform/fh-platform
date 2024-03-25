<?php

namespace FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderIndex;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Log;

class LogProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }
}
