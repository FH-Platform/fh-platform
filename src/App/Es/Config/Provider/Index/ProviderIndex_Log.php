<?php

namespace App\Es\Config\Provider\Index;

use App\Es\Config\Entity\Log;
use FHPlatform\ConfigBundle\Config\Provider\ProviderIndex;

class ProviderIndex_Log extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log::class;
    }

    public function getConnection(): string
    {
        return 'default';
    }
}
