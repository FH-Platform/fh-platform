<?php

namespace FHPlatform\ConfigBundle\Config\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
