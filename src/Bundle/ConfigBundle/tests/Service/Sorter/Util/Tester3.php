<?php

namespace FHPlatform\ConfigBundle\Tests\Service\Sorter\Util;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

class Tester3 implements PriorityInterface
{
    public function priority(): int
    {
        return -75;
    }
}
