<?php

namespace FHPlatform\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;

class DoctrineListenerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);
        $user->setTestString('test_string2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertEquals(1, 1);
    }
}
