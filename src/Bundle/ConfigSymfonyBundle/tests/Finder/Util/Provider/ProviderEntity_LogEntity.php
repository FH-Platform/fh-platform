<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntity;
use FHPlatform\ConfigSymfonyBundle\Tests\Finder\Util\Entity\LogEntity;

class ProviderEntity_LogEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return LogEntity::class;
    }
}
