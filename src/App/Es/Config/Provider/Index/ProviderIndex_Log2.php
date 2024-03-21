<?php

namespace App\Es\Config\Provider\Index;

use App\Es\Config\Entity\Log2;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\ProviderIndex;

class ProviderIndex_Log2 extends ProviderIndex
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
