<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use DecoratorBaseTrait;
    use DecoratorEntityTrait;
}
