<?php

namespace FHPlatform\ConfigBundle\Config\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
}
