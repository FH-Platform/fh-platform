<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

abstract class BaseDecorator implements PriorityInterface
{
    public function priority(): int
    {
        return 100;
    }
}
