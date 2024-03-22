<?php

namespace Fico7489\PersistenceDoctrineBundle\DoctrineListener;

use FHPlatform\Bundle\PersistenceBundle\DTO\ChangedEntityDTO;
use FHPlatform\Bundle\PersistenceBundle\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\TestCase;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Entity\User;

class DoctrineListenerBasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(ChangedEntityDTO::TYPE_CREATE, $value->getType());
        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());

        // test update
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $user->setNameText('name_text');
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(ChangedEntityDTO::TYPE_UPDATE, $value->getType());
        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(['nameText'], $value->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertCount(2, $this->eventsGet(ChangedEntitiesEvent::class));

        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(ChangedEntityDTO::TYPE_DELETE_PRE, $value->getType());
        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());

        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[1];
        $entities = $event->getChangedEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityDTO $value */
        $value = $entities[$key];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(ChangedEntityDTO::TYPE_DELETE, $value->getType());
        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
    }
}
