<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Entity\UserUuid;

class DoctrineListenerUuidTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new UserUuid();
        $user->setNameString('name_string');
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
        /** @var ChangedEntity $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_CREATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['uuid'], $value->getChangedFields());

        // test update
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $user->setNameString('name_string2');
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntity $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['nameString'], $value->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertCount(2, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[1];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntity $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntity::TYPE_DELETE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['uuid'], $value->getChangedFields());
    }
}
