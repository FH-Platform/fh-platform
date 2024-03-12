<?php

namespace FHPlatform\ConfigBundle\Service\Sorter;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

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
