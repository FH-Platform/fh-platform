<?php

namespace FHPlatform\Component\EventManager\Tests\EventManager;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(User::class)[0]);
        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(Role::class)[0]);

        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        // create
        $role = new Role();
        $role->setTestString('test');

        $user = new User();
        $user->setTestString('test');
        $user->addRole($role);

        $this->save([$role, $user]);
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        // update
        $user->setTestString('test2');
        $this->save([$user]);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        // delete
        $this->delete([$user]);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2'));
    }
}
