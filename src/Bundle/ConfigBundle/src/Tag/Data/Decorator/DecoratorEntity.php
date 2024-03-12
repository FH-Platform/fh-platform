<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityTrait;

abstract class DecoratorEntity implements DecoratorEntityInterface, DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}