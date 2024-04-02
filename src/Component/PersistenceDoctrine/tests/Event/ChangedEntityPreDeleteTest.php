<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityPreDeleteTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntityPreDelete::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setTestString('test_string2');
        $this->entityManager->persist($user2);

        $user3 = new User();
        $user3->setTestString('test_string3');
        $this->entityManager->persist($user3);

        $this->save([$user, $user2, $user3]);

        // test remove one
        $this->eventsClear(ChangedEntityPreDelete::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityPreDelete::class));
        $this->entityManager->remove($user);
        $events = $this->eventsGet(ChangedEntityPreDelete::class);

        /** @var ChangedEntityPreDelete $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());

        // test remove two
        $this->eventsClear(ChangedEntityPreDelete::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityPreDelete::class));
        $this->entityManager->remove($user2);
        $this->entityManager->remove($user3);
        $events = $this->eventsGet(ChangedEntityPreDelete::class);

        /** @var ChangedEntityPreDelete $event */
        $event = $events[0];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());

        /** @var ChangedEntityPreDelete $event */
        $event2 = $events[1];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event2->getClassName());
        $this->assertEquals(3, $event2->getIdentifierValue());
    }
}
