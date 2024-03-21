<?php

namespace FHPlatform\ConfigBundle\Config\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
