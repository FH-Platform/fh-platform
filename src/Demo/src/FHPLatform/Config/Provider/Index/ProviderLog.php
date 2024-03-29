<?php

namespace App\FHPLatform\Config\Provider\Index;

use App\FHPLatform\Config\Entity\Log;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class ProviderLog extends ProviderIndex
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
