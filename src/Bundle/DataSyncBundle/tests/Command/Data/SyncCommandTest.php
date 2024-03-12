<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Data;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Client\Query\QueryClient;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Helper\TaggedProviderMock;

class SyncCommandTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var QueryClient $queryClient */
        $queryClient = $this->container->get(QueryClient::class);

        /** @var IndexClient $indexClient */
        $indexClient = $this->container->get(IndexClient::class);

        $this->prepareUsers();

        $this->indexClient->recreateIndex(User::class);
        $this->assertCount(0, $queryClient->getResults(User::class));
        $this->commandHelper->runCommand(['command' => 'symfony-es:data:sync', 'class-name' => User::class]);
        $this->assertCount(2, $queryClient->getResults(User::class));
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
