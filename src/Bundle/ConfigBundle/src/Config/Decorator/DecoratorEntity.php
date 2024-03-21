<?php

namespace FHPlatform\ConfigBundle\Config\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
}
