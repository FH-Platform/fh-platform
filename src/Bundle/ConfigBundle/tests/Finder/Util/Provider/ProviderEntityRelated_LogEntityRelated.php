<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated;

class ProviderEntityRelated_LogEntityRelated extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return LogEntityRelated::class;
    }
}
