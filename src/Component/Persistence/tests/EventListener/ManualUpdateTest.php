<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Manager\EventManager;
use FHPlatform\Component\Persistence\Tests\TestCase;

class ManualUpdateTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->container->get(EventManager::class);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        /** @var EntitiesRelatedBuilder $entitiesRelatedBuilder */
        $entitiesRelatedBuilder = $this->container->get(EntitiesRelatedBuilder::class);

        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(User::class)[0]);
        $this->indexClient->recreateIndex($connectionsBuilder->fetchIndexesByClassName(Role::class)[0]);

        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(0, $this->findEsBy(Role::class, 'user.testString', 'test'));

        // delete
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));

        $role = new Role();
        $this->save([$role]);

        $user = new User();
        $user->setTestString('test');
        $user->addRole($role);
        $this->save([$user]);

        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $relatedEntities = $entitiesRelatedBuilder->buildForEntity($connectionsBuilder->build()[0], $user);
        $this->entityManager->createQuery('DELETE FROM '.User::class.' e WHERE e.id = 1')->execute();
        $this->assertEquals([1], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([1], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $eventManager->syncEntitiesManually($relatedEntities);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));

        // update
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test'));

        $role = new Role();
        $this->save([$role]);

        $user = new User();
        $user->setTestString('test');
        $user->addRole($role);
        $this->save([$user]);

        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $this->entityManager->createQuery('UPDATE '.User::class." e SET e.testString = 'test2' WHERE e.id = 2")->execute();
        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test'));
        $eventManager->syncEntitiesManually([User::class => [2]]);
        $this->assertEquals([2], $this->findEsBy(User::class, 'testString', 'test2'));
        $this->assertEquals([2], $this->findEsBy(Role::class, 'users.testString', 'test2'));

        // create
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test3'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test3'));
        $this->entityManager->getConnection()->insert('user', [
            'testString' => 'test3',
        ]);
        $this->entityManager->getConnection()->insert('role', [
            'testString' => 'test3',
        ]);
        $this->entityManager->getConnection()->insert('user_role', [
            'user_id' => 3,
            'role_id' => 3,
        ]);
        $this->assertEquals([], $this->findEsBy(User::class, 'testString', 'test3'));
        $this->assertEquals([], $this->findEsBy(Role::class, 'users.testString', 'test3'));
        $eventManager->syncEntitiesManually([User::class => [3]]);
        $this->assertEquals([3], $this->findEsBy(User::class, 'testString', 'test3'));

        //TODO
        $this->assertEquals([3], $this->findEsBy(Role::class, 'users.testString', 'test3'));
    }
}
