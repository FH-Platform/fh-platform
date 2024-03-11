<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class EntityRelated  implements DecoratorEntityRelatedInterface
{
    use PriorityTrait;
    use DecoratorEntityRelatedTrait;
}
