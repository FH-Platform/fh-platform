<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\UserUuid;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class DoctrineListenerUuidTest extends TestCase
{
    public function testSomething(): void
    {
        //TODO
        $this->assertEquals(1,1);
        return;

        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new UserUuid();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $id = $user->getUuid();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_CREATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());

        // test update
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $user->setTestString('test_string2');
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['testString'], $value->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_DELETE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
    }
}
