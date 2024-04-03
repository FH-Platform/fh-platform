<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\FlushEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class FlushTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(FlushEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(FlushEvent::class);
        $this->assertCount(0, $this->eventsGet(FlushEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(FlushEvent::class));

        $this->eventsClear(FlushEvent::class);
        $this->entityManager->flush();
        $this->entityManager->flush();
        $this->assertCount(2, $this->eventsGet(FlushEvent::class));
    }
}
