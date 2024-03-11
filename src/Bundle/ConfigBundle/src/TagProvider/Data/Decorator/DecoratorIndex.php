<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use DecoratorBaseTrait;
    use DecoratorIndexTrait;
}
