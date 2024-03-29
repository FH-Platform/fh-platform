<?php

namespace App\FHPlatform\Config\Provider\Index;

use App\FHPlatform\Config\Entity\Log3;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class ProviderLog3 extends ProviderIndex
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
