<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use DecoratorBaseTrait;
    use DecoratorIndexTrait;
}
