<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class ChangedFieldsTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntityEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // test persist one
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));

        $user->setTestString('test_string2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->assertEquals(['testString'], $this->eventsGet(ChangedEntityEvent::class)[0]->getChangedFields());

        // test persist two
        $this->eventsClear(ChangedEntityEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntityEvent::class));
        $user->setTestString('test_string3');
        $user->setTestFloat(16.2);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertEquals(['testFloat', 'testString'], $this->eventsGet(ChangedEntityEvent::class)[0]->getChangedFields());
    }
}
