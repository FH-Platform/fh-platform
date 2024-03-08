<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderIndex;

class TestProviderIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return 'TestProviderIndex';
    }
}
