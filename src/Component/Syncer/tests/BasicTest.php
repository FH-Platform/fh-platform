<?php

namespace FHPlatform\Component\Syncer\Tests;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertEquals(1, 1);
    }
}
