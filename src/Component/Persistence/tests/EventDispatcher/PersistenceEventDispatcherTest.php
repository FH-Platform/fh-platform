<?php

namespace FHPlatform\Persistence\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\FlushEvent;
use FHPlatform\Component\Persistence\EventDispatcher\PersistenceEventDispatcher;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class PersistenceEventDispatcherTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var PersistenceEventDispatcher $eventManager */
        $eventManager = $this->container->get(PersistenceEventDispatcher::class);

        $this->eventsStartListen(ChangedEntityEvent::class);

        // post create
        $this->eventsClear(ChangedEntityEvent::class);
        $eventManager->dispatchPostCreateEntity(User::class, 1);
        /** @var ChangedEntityEvent $event */
        $event = $this->eventsGet(ChangedEntityEvent::class)[0];

        $this->assertEquals(User::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        // post update
        $this->eventsClear(ChangedEntityEvent::class);
        $eventManager->dispatchPostUpdateEntity(Role::class, 2, ['test', 'test2']);
        /** @var ChangedEntityEvent $event */
        $event = $this->eventsGet(ChangedEntityEvent::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(2, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $event->getType());
        $this->assertEquals(['test', 'test2'], $event->getChangedFields());

        // post delete
        $this->eventsClear(ChangedEntityEvent::class);
        $eventManager->dispatchPostDeleteEntity(Role::class, 3);
        /** @var ChangedEntityEvent $event */
        $event = $this->eventsGet(ChangedEntityEvent::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(3, $event->getIdentifierValue());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $event->getType());
        $this->assertEquals([], $event->getChangedFields());

        // pre delete
        $this->eventsStartListen(ChangedEntityEvent::class);
        $this->eventsClear(ChangedEntityEvent::class);
        $eventManager->dispatchPreDeleteEntity(Role::class, 1);
        /** @var ChangedEntityEvent $event */
        $event = $this->eventsGet(ChangedEntityEvent::class)[0];

        $this->assertEquals(Role::class, $event->getClassName());
        $this->assertEquals(1, $event->getIdentifierValue());

        // flush
        $this->eventsStartListen(FlushEvent::class);
        $this->eventsClear(FlushEvent::class);
        $eventManager->dispatchFlush();
        /** @var FlushEvent $event */
        $event = $this->eventsGet(FlushEvent::class)[0];

        $this->assertNotNull($event);
    }
}
