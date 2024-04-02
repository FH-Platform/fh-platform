<?php

namespace FHPlatform\Persistence\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\Persistence\EventDispatcher\PersistenceEventDispatcher;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class PersistenceEventDispatcherTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var PersistenceEventDispatcher $eventManager */
        $eventManager = $this->container->get(PersistenceEventDispatcher::class);

        $this->eventsStartListen(ChangedEntity::class);

        //post create
        $this->eventsClear(ChangedEntity::class);
        $eventManager->dispatchPostCreateEntity(User::class, 1);
        /** @var ChangedEntity $event */
        $event = $this->eventsGet(ChangedEntity::class)[0];

        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        //post update
        $this->eventsClear(ChangedEntity::class);
        $eventManager->dispatchPostUpdateEntity(Role::class, 2, ['test', 'test2']);
        /** @var ChangedEntity $event */
        $event = $this->eventsGet(ChangedEntity::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['test', 'test2'], $event->getChangedFields());

        //post delete
        $this->eventsClear(ChangedEntity::class);
        $eventManager->dispatchPostDeleteEntity(Role::class, 3);
        /** @var ChangedEntity $event */
        $event = $this->eventsGet(ChangedEntity::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(3, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntity::TYPE_DELETE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());


        //pre delete
        $this->eventsStartListen(ChangedEntityPreDelete::class);
        $this->eventsClear(ChangedEntityPreDelete::class);
        $eventManager->dispatchPreDeleteEntity(Role::class, 1);
        /** @var ChangedEntityPreDelete $event */
        $event = $this->eventsGet(ChangedEntityPreDelete::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());

        //flush
        $this->eventsStartListen(Flush::class);
        $this->eventsClear(Flush::class);
        $eventManager->dispatchFlush();
        /** @var Flush $event */
        $event = $this->eventsGet(Flush::class)[0];

        $this->assertNotNull($event);
    }
}
