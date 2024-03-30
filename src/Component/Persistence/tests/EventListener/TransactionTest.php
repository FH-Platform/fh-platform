<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Persistence\Manager\EventManager;
use FHPlatform\Component\Persistence\Tests\TestCase;
use FHPlatform\Component\Persistence\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Connections\ProviderDefault;
use FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider\UserProvider;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefault::class,
            UserProvider::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var EventManager $eventManager */
        $eventManager = $this->container->get(EventManager::class);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test2'));

        //delete
        $this->entityManager->getConnection()->beginTransaction();
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test'));
        $eventManager->syncEntitiesManually(User::class, [1]);
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test'));

        // update
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);

        $this->entityManager->getConnection()->beginTransaction();
        $user->setNameString('test2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test2'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test2'));
        $eventManager->syncEntitiesManually(User::class, [2]);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test2'));

        //create
        $this->entityManager->getConnection()->beginTransaction();
        $user = new User();
        $user->setNameString('test3');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test3'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertCount(1, $this->findEsBy(User::class, 'nameString', 'test3'));
        $eventManager->syncEntitiesManually(User::class, [3]);
        $this->assertCount(0, $this->findEsBy(User::class, 'nameString', 'test3'));
    }
}
