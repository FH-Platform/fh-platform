<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;

class TestProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'TestProviderEntity';
    }
}
