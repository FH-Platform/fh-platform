<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
}
