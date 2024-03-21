<?php

namespace FHPlatform\ConfigBundle\Config\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
