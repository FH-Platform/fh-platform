<?php

namespace App\FHPlatform\Config\Provider\Index;

use App\FHPlatform\Config\Entity\Log2;
use FHPlatform\Component\Config\Config\Provider\ProviderIndex;

class ProviderLog2 extends ProviderIndex
{
    public function getClassName(): string
    {
        return Log2::class;
    }

    public function getConnection(): string
    {
        return 'default';
    }
}
