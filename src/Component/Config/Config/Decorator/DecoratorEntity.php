<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Util\Sorter\Trait\PriorityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
}
