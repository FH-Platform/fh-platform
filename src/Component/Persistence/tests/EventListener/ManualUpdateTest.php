<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
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

        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));

        // delete
        $user = new User();
        $user->setTestString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test'));
        $this->entityManager->createQuery('DELETE FROM '.User::class.' e WHERE e.id = 1')->execute();
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test'));
        $eventManager->syncEntitiesManually(User::class, [1]);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));

        // update
        $user = new User();
        $user->setTestString('test');
        $this->save([$user]);

        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test'));
        $this->entityManager->createQuery('UPDATE '.User::class." e SET e.testString = 'test2' WHERE e.id = 2")->execute();
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test2'));
        $eventManager->syncEntitiesManually(User::class, [2]);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test'));
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test2'));

        // create
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test3'));
        $this->entityManager->getConnection()->insert('user', [
            'testString' => 'test3',
        ]);
        $this->assertCount(0, $this->findEsBy(User::class, 'testString', 'test3'));
        $eventManager->syncEntitiesManually(User::class, [3]);
        $this->assertCount(1, $this->findEsBy(User::class, 'testString', 'test3'));
    }
}
