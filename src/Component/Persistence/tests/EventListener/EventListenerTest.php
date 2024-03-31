<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\TestCase;

class EventListenerTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(User::class)[0]);
        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(Role::class)[0]);

        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));

        // create
        $user = new User();
        $user->setTestString('test');
        $this->save([$user]);
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));

        // update
        $user->setTestString('test2');
        $this->save([$user]);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test2'));

        // delete
        $this->delete([$user]);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
    }
}
