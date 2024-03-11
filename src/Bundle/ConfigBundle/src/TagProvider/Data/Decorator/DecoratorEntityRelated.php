<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityRelatedTrait;

abstract class DecoratorEntityRelated extends DecoratorBase implements EntityRelatedInterface
{
    use EntityRelatedTrait;
}
