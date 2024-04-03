<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityMoreTest extends TestCase
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

        $user4 = new User();
        $user4->setTestString('test_string4');
        $this->entityManager->persist($user4);

        $role = new Role();
        $role->setTestString('test_string');
        $this->entityManager->persist($role);

        $role2 = new Role();
        $role2->setTestString('test_string2');
        $this->entityManager->persist($role2);

        $this->save([$user, $user2, $user3, $role]);

        // clear events
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));

        // update 2 users
        $user->setTestString('test_string_2');
        $user2->setTestString('test_string2_2');
        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);

        // update 1 role
        $role->setTestString('test_string_2');
        $this->entityManager->persist($role);

        // delete 2 users
        $this->entityManager->remove($user3);
        $this->entityManager->remove($user4);

        // delete 1 role
        $this->entityManager->remove($role2);

        // create 2 users
        $user5 = new User();
        $user5->setTestString('test_string5');
        $this->entityManager->persist($user5);

        $user6 = new User();
        $user6->setTestString('test_string6');
        $this->entityManager->persist($user6);

        // create one role
        $role3 = new Role();
        $role3->setTestString('test_string3');
        $this->entityManager->persist($role3);

        $this->entityManager->flush();

        /** @var ChangedEntityEvent[] $events */
        $events = $this->eventsGet(ChangedEntityEvent::class);

        $this->assertCount(12, $this->eventsGet(ChangedEntityEvent::class));

        $event = $events[0];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(3, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE_PRE,$event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[1];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(4, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE_PRE,$event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[2];
        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE_PRE,$event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $this->assertEquals(User::class, $events[0]->getClassName());
        $event = $events[3];
        $this->assertEquals(5, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[4];
        $this->assertEquals(User::class,$event->getClassName());
        $this->assertEquals(6, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[5];
        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(3, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[6];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['testString'], $event->getChangedFields());

        $event = $events[7];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['testString'], $event->getChangedFields());

        $event = $events[8];
        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['testString'], $event->getChangedFields());

        $event = $events[9];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(3, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $event->getType());
        $this->assertEquals([],$event->getChangedFields());

        $event = $events[10];
        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(4, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        $event = $events[11];
        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE,$event->getType());
        $this->assertEquals([], $event->getChangedFields());
    }
}
