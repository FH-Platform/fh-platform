<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\EntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Decorator\Trait\EntityTrait;

abstract class EntityDecorator extends BaseDecorator implements EntityInterface
{
    use EntityTrait;
}
