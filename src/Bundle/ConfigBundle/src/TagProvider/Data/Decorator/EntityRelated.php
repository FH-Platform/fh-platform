<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;

abstract class EntityRelated  implements DecoratorEntityRelatedInterface
{
    use DecoratorBaseTrait;
    use DecoratorEntityRelatedTrait;
}
