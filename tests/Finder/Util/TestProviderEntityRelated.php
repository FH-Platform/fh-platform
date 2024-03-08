<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntityRelated;

class TestProviderEntityRelated extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return 'TestProviderEntityRelated';
    }
}
