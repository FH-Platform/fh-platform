<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityRelatedTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
