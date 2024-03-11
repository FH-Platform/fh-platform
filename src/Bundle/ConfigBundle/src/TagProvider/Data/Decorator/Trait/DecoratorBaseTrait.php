<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait;

use FHPlatform\ConfigBundle\Util\Namer\IndexNamer;

trait DecoratorBaseTrait
{
    public function priority(): int
    {
        return 100;
    }
}
