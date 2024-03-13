<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorEntityRelatedTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
