<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorIndexTrait;

abstract class DecoratorIndex implements DecoratorIndexInterface
{
    use PriorityTrait;
    use DecoratorIndexTrait;
}
