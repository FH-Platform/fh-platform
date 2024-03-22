<?php

namespace Fico7489\PersistenceDoctrine\DoctrineListener;

use FHPlatform\Bundle\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Bundle\PersistenceDoctrine\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;

class DoctrineListenerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);
        $user->setNameString('name_string2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertEquals(1, 1);
    }
}
