<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class FlushTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(Flush::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(Flush::class);
        $this->assertCount(0, $this->eventsGet(Flush::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(Flush::class));

        $this->eventsClear(Flush::class);
        $this->entityManager->flush();
        $this->entityManager->flush();
        $this->assertCount(2, $this->eventsGet(Flush::class));
    }
}
