<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntityEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntityEvent::class));

        $events = $this->eventsGet(ChangedEntityEvent::class);

        /** @var ChangedEntityEvent $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        // test update
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $user->setTestString('test_string2');
        $this->entityManager->flush();

        $events = $this->eventsGet(ChangedEntityEvent::class);

        /** @var ChangedEntityEvent $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['testString'], $event->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $events = $this->eventsGet(ChangedEntityEvent::class);

        /** @var ChangedEntityEvent $event */
        $event = $events[0];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE_PRE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        /** @var ChangedEntityEvent $event2 */
        $event2 = $events[1];
        $this->assertEquals(User::class, $event2->getClassName());
        $this->assertEquals(1, $event2->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $event2->getType());
        $this->assertEquals([], $event2->getChangedFields());
    }
}
