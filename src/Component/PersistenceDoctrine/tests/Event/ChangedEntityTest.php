<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntity::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(ChangedEntity::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntity::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntity::class));

        $events = $this->eventsGet(ChangedEntity::class);

        /** @var ChangedEntity $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        // test update
        $this->eventsClear(ChangedEntity::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntity::class));
        $user->setTestString('test_string2');
        $this->entityManager->flush();

        $events = $this->eventsGet(ChangedEntity::class);

        /** @var ChangedEntity $event */
        $event = $events[0];
        $this->assertCount(1, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['testString'], $event->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntity::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntity::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $events = $this->eventsGet(ChangedEntity::class);

        /** @var ChangedEntity $event */
        $event = $events[0];
        $this->assertCount(2, $events);
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_DELETE_PRE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        /** @var ChangedEntity $event2 */
        $event2 = $events[1];
        $this->assertEquals(User::class, $event2->getClassName());
        $this->assertEquals(1, $event2->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_DELETE, $event2->getType());
        $this->assertEquals([], $event2->getChangedFields());
    }
}
