<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\UpdatingMap;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class BasicTest extends TestCaseUpdatingMap
{
    public function testSomething(): void
    {
        $index = new Index(new Connection('test', 'test', []), User::class, '', '', []);

        $this->assertEquals(1, 1);
    }
}
