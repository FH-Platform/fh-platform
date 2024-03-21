<?php

namespace App\Es\Config\Provider\Index;

use App\Es\Config\Entity\Log3;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class ProviderIndex_Log3 extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log3::class;
    }

    public function getConnection(): string
    {
        return 'another';
    }
}
