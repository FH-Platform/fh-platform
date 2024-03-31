<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class DoctrineListenerMoreEventTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        // one event -> 2 updates
        $this->eventsClear(ChangedEntitiesEvent::class);
        $user = new User();
        $user->setTestString('test_string');

        $user2 = new User();
        $user2->setTestString('test_string2');

        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);
        $this->entityManager->flush();

        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->assertCount(2, $this->eventsGet(ChangedEntitiesEvent::class)[0]->getChangedEntities());

        // two events -> 1 remove_post +  1 updates, 1 remove
        $this->eventsClear(ChangedEntitiesEvent::class);
        $user->setTestString('test_string3');
        $this->entityManager->remove($user2);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->assertCount(2, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class)[0]->getChangedEntities());
        $this->assertCount(2, $this->eventsGet(ChangedEntitiesEvent::class)[1]->getChangedEntities());
    }
}
