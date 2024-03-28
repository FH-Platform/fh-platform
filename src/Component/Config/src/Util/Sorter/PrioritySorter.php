<?php

namespace FHPlatform\Component\Config\Util\Sorter;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorBaseInterface;

class PrioritySorter
{
    /** @param DecoratorBaseInterface[] $decorators */
    public function sort(array $decorators): array
    {
        uasort($decorators, function ($decorator, $decorator2) {
            /* @var DecoratorBaseInterface $decorator */
            /* @var DecoratorBaseInterface $decorator2 */

            return $decorator->priority() > $decorator2->priority() ? 1 : -1;
        });

        return $decorators;
    }
}
