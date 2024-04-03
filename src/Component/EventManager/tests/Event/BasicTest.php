<?php

namespace FHPlatform\EventManager\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(SyncEntitiesEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);
        $this->assertCount(0, $this->eventsGet(SyncEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(SyncEntitiesEvent::class));

        $this->eventsClear(SyncEntitiesEvent::class);
        $user2 = new User();
        $user2->setTestString('test_string');
        $this->entityManager->persist($user2);
        $this->entityManager->flush();
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(SyncEntitiesEvent::class));

        $this->eventsClear(SyncEntitiesEvent::class);
        $user3 = new User();
        $user3->setTestString('test_string3');

        $user4 = new User();
        $user4->setTestString('test_string4');

        $this->save([$user3, $user3]);
        $this->assertCount(1, $this->eventsGet(SyncEntitiesEvent::class));
    }
}
