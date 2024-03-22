<?php

namespace Fico7489\PersistenceDoctrineBundle\DoctrineListener;

use FHPlatform\Bundle\PersistenceBundle\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\TestCase;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Entity\User;

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
