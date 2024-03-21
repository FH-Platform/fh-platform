<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
