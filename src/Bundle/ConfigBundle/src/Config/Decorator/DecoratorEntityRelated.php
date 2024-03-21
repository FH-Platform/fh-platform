<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
