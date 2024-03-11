<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\IndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\IndexTrait;

abstract class DecoratorIndex extends DecoratorBase implements IndexInterface
{
    use IndexTrait;
}
