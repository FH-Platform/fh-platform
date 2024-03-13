<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorEntityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
}
