<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityPreDeleteTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntityEvent::class);

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
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $this->entityManager->remove($user);
        $events = $this->eventsGet(ChangedEntityEvent::class);

        /** @var ChangedEntityEvent $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE_PRE, $event->getType());

        // test remove two
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $this->entityManager->remove($user2);
        $this->entityManager->remove($user3);
        $events = $this->eventsGet(ChangedEntityEvent::class);

        /** @var ChangedEntityEvent $event */
        $event = $events[0];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());

        /** @var ChangedEntityEvent $event */
        $event2 = $events[1];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event2->getClassName());
        $this->assertEquals(3, $event2->getIdentifierValue());
    }
}
