<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
