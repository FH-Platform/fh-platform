<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

abstract class DecoratorBase implements PriorityInterface
{
    public function priority(): int
    {
        return 100;
    }
}
