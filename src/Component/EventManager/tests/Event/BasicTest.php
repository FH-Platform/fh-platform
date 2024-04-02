<?php

namespace FHPlatform\EventManager\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Component\EventManager\Event\ChangedEntities;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntities::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);
        $this->assertCount(0, $this->eventsGet(ChangedEntities::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntities::class));

        $this->eventsClear(ChangedEntities::class);
        $user2 = new User();
        $user2->setTestString('test_string');
        $this->entityManager->persist($user2);
        $this->entityManager->flush();
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntities::class));

        $this->eventsClear(ChangedEntities::class);
        $user3 = new User();
        $user3->setTestString('test_string3');

        $user4 = new User();
        $user4->setTestString('test_string4');

        $this->save([$user3, $user3]);
        $this->assertCount(1, $this->eventsGet(ChangedEntities::class));
    }
}
