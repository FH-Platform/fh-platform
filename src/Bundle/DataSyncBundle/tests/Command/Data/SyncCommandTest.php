<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Command\Data;

use FHPlatform\Bundle\ClientBundle\Client\Index\IndexClient;
use FHPlatform\Bundle\ClientBundle\Client\Query\QueryClient;
use FHPlatform\Bundle\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\Bundle\ConfigBundle\Config\ConfigProvider;
use FHPlatform\Bundle\DataSyncBundle\Tests\TestCase;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

class SyncCommandTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        /** @var QueryClient $queryClient */
        $queryClient = $this->container->get(QueryClient::class);

        /** @var IndexClient $indexClient */
        $indexClient = $this->container->get(IndexClient::class);

        $this->prepareUsers();

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $queryClient->getResults($index));
        $this->commandHelper->runCommand(['command' => 'symfony-es:data:sync', 'class-name' => User::class]);
        $this->assertCount(2, $queryClient->getResults($index));
    }

    private function prepareUsers()
    {
        for ($i = 0; $i < 2; ++$i) {
            $user = new User();
            $user->setNameString('name_'.random_int(1, 10000));
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
