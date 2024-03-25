<?php

namespace Fico7489\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Tests\TestCase;
use FHPlatform\Component\Persistence\Tests\Util\Entity\User;

class DoctrineListenerMoreSameTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setNameString('name_string2');
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

        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntityDTO::TYPE_CREATE, $value->getType());
        $this->assertEquals(ChangedEntityDTO::TYPE_CREATE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
        $this->assertEquals(['id'], $value2->getChangedFields());

        // test update
        $user->setNameString('name_string_1');
        $user->setNameText('name_text_1');
        $user2->setNameText('name_text2_1');
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

        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntityDTO::TYPE_UPDATE, $value->getType());
        $this->assertEquals(ChangedEntityDTO::TYPE_UPDATE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(['nameString', 'nameText'], $value->getChangedFields());
        $this->assertEquals(['nameText'], $value2->getChangedFields());

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

        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());

        $this->assertEquals(ChangedEntityDTO::TYPE_DELETE, $value->getType());
        $this->assertEquals(ChangedEntityDTO::TYPE_DELETE, $value2->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
        $this->assertEquals(['id'], $value2->getChangedFields());
    }
}
