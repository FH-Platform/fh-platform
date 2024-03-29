<?php

namespace App\FHPLatform\Config\Provider\Index;

use App\FHPLatform\Config\Entity\Log3;
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
