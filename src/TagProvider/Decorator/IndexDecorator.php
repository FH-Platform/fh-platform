<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\IndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Decorator\Trait\IndexTrait;

abstract class IndexDecorator extends BaseDecorator implements IndexInterface
{
    use IndexTrait;
}
