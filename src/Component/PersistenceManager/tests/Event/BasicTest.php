<?php

namespace FHPlatform\PersistenceManager\DoctrineListener;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Component\PersistenceManager\Event\ChangedEntities;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntities::class);

        $user = new User();
        $user->setTestString('test_string');
        $this->entityManager->persist($user);
        $this->assertCount(0, $this->eventsGet(ChangedEntities::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntities::class));
    }
}
