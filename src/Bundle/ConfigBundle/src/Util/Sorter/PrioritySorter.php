<?php

namespace FHPlatform\ConfigBundle\Util\Sorter;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

class PrioritySorter
{
    public function sort($decorators)
    {
        uasort($decorators, function ($decorator, $decorator2) {
            /* @var PriorityInterface $decorator */
            /* @var PriorityInterface $decorator2 */

            return $decorator->priority() > $decorator2->priority() ? 1 : -1;
        });

        return $decorators;
    }
}
