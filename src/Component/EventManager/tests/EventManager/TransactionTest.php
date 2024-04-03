<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\EventManager\Manager\EventManager;
use FHPlatform\Component\Persistence\Tests\TestCase;

class TransactionTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->container->get(EventManager::class);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(User::class)[0]);
        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(Role::class)[0]);

        // transaction rollback delete
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));

        $role = new Role();
        $role->setTestString('test');
        $this->save([$role]);

        $user = new User();
        $user->setTestString('test');
        $user->addRole($role);
        $this->save([$user]);

        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $eventManager->beginTransactionAction();
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $eventManager->rollbackTransactionAction();
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test'));

        // transaction rollback update
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        $role = new Role();
        $role->setTestString('test2');
        $this->save([$role]);

        $user = new User();
        $user->addRole($role);
        $user->setTestString('test2');
        $this->save([$user]);

        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test2'));
        $this->entityManager->getConnection()->beginTransaction();
        $eventManager->beginTransactionAction();
        $user->setTestString('test2_2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test2_2'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test2_2'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test2_2'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test2_2'));
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2'));
        $eventManager->rollbackTransactionAction();
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test2_2'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test2_2'));
        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        // transaction rollback create
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test3'));
        $this->entityManager->getConnection()->beginTransaction();
        $eventManager->beginTransactionAction();
        $user = new User();
        $user->setTestString('test3');
        $this->save([$user]);
        $this->assertEquals([3], $this->findEsBy(User::class, 'testString', 'test3'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertEquals([3], $this->findEsBy(User::class, 'testString', 'test3'));
        $eventManager->rollbackTransactionAction();
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test3'));
    }
}
