<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;

abstract class DecoratorEntityRelated implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
