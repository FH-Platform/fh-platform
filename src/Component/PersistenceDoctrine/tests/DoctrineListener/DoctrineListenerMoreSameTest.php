<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class DoctrineListenerMoreSameTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setTestString('test_string2');
        $this->entityManager->persist($user2);

        // test persist
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(2, $entities);

        list($key, $key2) = array_keys($entities);

        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntity::TYPE_CREATE, $value->getType());
        $this->assertEquals(ChangedEntity::TYPE_CREATE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());

        // test update
        $user->setTestString('test_string2');
        $user->setTestText('test_text2');
        $user2->setTestText('test_text2');
        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);

        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(2, $entities);

        list($key, $key2) = array_keys($entities);

        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $value->getType());
        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(['testString', 'testText'], $value->getChangedFields());
        $this->assertEquals(['testText'], $value2->getChangedFields());

        // test delete
        $this->entityManager->remove($user);
        $this->entityManager->remove($user2);

        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(2, $entities);

        list($key, $key2) = array_keys($entities);
        S
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntity::TYPE_DELETE, $value->getType());
        $this->assertEquals(ChangedEntity::TYPE_DELETE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
    }
}
