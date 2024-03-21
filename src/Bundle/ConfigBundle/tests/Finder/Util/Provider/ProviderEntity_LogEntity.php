<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity;

class ProviderEntity_LogEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return LogEntity::class;
    }
}
