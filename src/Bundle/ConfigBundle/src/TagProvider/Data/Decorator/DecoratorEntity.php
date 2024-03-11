<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityTrait;

abstract class DecoratorEntity extends DecoratorBase implements EntityInterface, EntityRelatedInterface
{
    use EntityTrait;
    use EntityRelatedTrait;
}
