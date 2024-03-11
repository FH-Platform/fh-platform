<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated;

class ProviderEntity_LogEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return LogEntity::class;
    }
}
