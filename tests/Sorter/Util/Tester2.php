<?php

namespace FHPlatform\ConfigBundle\Tests\Sorter\Util;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

class Tester2 implements PriorityInterface
{
    public function priority(): int
    {
        return 75;
    }
}
