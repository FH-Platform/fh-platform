<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityTrait;

abstract class DecoratorEntity  implements DecoratorEntityInterface, DecoratorEntityRelatedInterface
{
    use DecoratorBaseTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
