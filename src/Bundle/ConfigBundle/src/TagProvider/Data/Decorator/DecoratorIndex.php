<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
