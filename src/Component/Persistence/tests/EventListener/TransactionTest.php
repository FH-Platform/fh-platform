<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Persistence\Tests\TestCase;
use FHPlatform\Component\Persistence\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Connections\ProviderDefault;
use FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider\UserProvider;
use FHPlatform\Component\SearchEngine\Manager\DataManager;

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
        /** @var DataManager $dataManager */
        $dataManager = $this->container->get(DataManager::class);

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // create
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);

        $this->entityManager->getConnection()->beginTransaction();
        $user->setNameString('test2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test2'));
        $this->entityManager->getConnection()->rollBack();
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test2'));
        $dataManager->syncByClassName(User::class, [1]);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // TODO

        // $this->entityManager->refresh($user);
        // dd($user);
    }
}
