<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\TestCase;

class EventListenerTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test2'));

        // create
        $user = new User();
        $user->setTestString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test2'));

        // update
        $user->setTestString('test2');
        $this->save([$user]);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test2'));

        // delete
        $this->delete([$user]);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test2'));
    }
}
