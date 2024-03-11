<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
