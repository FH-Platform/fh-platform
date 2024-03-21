<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Service\Sorter\Util;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

class Tester implements PriorityInterface
{
    public function priority(): int
    {
        return 50;
    }
}
