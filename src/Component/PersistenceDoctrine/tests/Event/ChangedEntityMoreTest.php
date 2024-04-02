<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedEntityMoreTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntity::class);

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

        //clear events
        $this->eventsClear(ChangedEntity::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntity::class));

        //update 2 users
        $user->setTestString('test_string_2');
        $user2->setTestString('test_string2_2');
        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);

        //update 1 role
        $role->setTestString('test_string_2');
        $this->entityManager->persist($role);

        //delete 2 users
        $this->entityManager->remove($user3);
        $this->entityManager->remove($user4);

        //delete 1 role
        $this->entityManager->remove($role2);

        //create 2 users
        $user5 = new User();
        $user5->setTestString('test_string5');
        $this->entityManager->persist($user5);

        $user6 = new User();
        $user6->setTestString('test_string6');
        $this->entityManager->persist($user6);

        //create one role
        $role3 = new Role();
        $role3->setTestString('test_string3');
        $this->entityManager->persist($role3);

        $this->entityManager->flush();

        /** @var ChangedEntity[] $events */
        $events = $this->eventsGet(ChangedEntity::class);

        $this->assertCount(9, $this->eventsGet(ChangedEntity::class));

        $this->assertEquals(User::class, $events[0]->getClassName());
        $this->assertEquals(5, $events[0]->getIdentifierValue());
        $this->assertEquals('create', $events[0]->getType());
        $this->assertEquals([], $events[0]->getChangedFields());

        $this->assertEquals(User::class, $events[1]->getClassName());
        $this->assertEquals(6, $events[1]->getIdentifierValue());
        $this->assertEquals('create', $events[1]->getType());
        $this->assertEquals([], $events[1]->getChangedFields());

        $this->assertEquals(Role::class, $events[2]->getClassName());
        $this->assertEquals(3, $events[2]->getIdentifierValue());
        $this->assertEquals('create', $events[2]->getType());
        $this->assertEquals([], $events[2]->getChangedFields());

        $this->assertEquals(User::class, $events[3]->getClassName());
        $this->assertEquals(1, $events[3]->getIdentifierValue());
        $this->assertEquals('update', $events[3]->getType());
        $this->assertEquals(['testString'], $events[3]->getChangedFields());

        $this->assertEquals(User::class, $events[4]->getClassName());
        $this->assertEquals(2, $events[4]->getIdentifierValue());
        $this->assertEquals('update', $events[4]->getType());
        $this->assertEquals(['testString'], $events[4]->getChangedFields());

        $this->assertEquals(Role::class, $events[5]->getClassName());
        $this->assertEquals(1, $events[5]->getIdentifierValue());
        $this->assertEquals('update', $events[5]->getType());
        $this->assertEquals(['testString'], $events[5]->getChangedFields());

        $this->assertEquals(User::class, $events[6]->getClassName());
        $this->assertEquals(3, $events[6]->getIdentifierValue());
        $this->assertEquals('delete', $events[6]->getType());
        $this->assertEquals([], $events[6]->getChangedFields());

        $this->assertEquals(User::class, $events[7]->getClassName());
        $this->assertEquals(4, $events[7]->getIdentifierValue());
        $this->assertEquals('delete', $events[7]->getType());
        $this->assertEquals([], $events[7]->getChangedFields());

        $this->assertEquals(Role::class, $events[8]->getClassName());
        $this->assertEquals(2, $events[8]->getIdentifierValue());
        $this->assertEquals('delete', $events[8]->getType());
        $this->assertEquals([], $events[8]->getChangedFields());
    }
}
