<?php

namespace FHPlatform\ConfigBundle\Tests\Service\Sorter;

use FHPlatform\ConfigBundle\Service\Sorter\PrioritySorter;
use FHPlatform\ConfigBundle\Tests\Service\Sorter\Util\Tester;
use FHPlatform\ConfigBundle\Tests\Service\Sorter\Util\Tester2;
use FHPlatform\ConfigBundle\Tests\Service\Sorter\Util\Tester3;
use FHPlatform\ConfigBundle\Tests\Service\Sorter\Util\Tester4;
use FHPlatform\ConfigBundle\Tests\TestCase;

class PrioritySorterTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var PrioritySorter $prioritySorter */
        $prioritySorter = $this->container->get(PrioritySorter::class);

        $sorted = $prioritySorter->sort([
            new Tester(),
            new Tester2(),
            new Tester3(),
            new Tester4(),
        ]);

        $sorted = array_values($sorted);

        $this->assertEquals(Tester3::class, get_class($sorted[0])); // -75
        $this->assertEquals(Tester4::class, get_class($sorted[1])); // -50
        $this->assertEquals(Tester::class, get_class($sorted[2]));  // 50
        $this->assertEquals(Tester2::class, get_class($sorted[3])); // 75
    }
}
