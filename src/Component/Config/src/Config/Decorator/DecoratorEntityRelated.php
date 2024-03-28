<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use DecoratorBaseTrait;
    use DecoratorEntityRelatedTrait;
}
