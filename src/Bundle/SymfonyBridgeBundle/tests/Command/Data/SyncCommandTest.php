<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Command\Data;

use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\TestCase;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class SyncCommandTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        /** @var QueryManager $queryClient */
        $queryClient = $this->container->get(QueryManager::class);

        /** @var IndexManager $indexClient */
        $indexClient = $this->container->get(IndexManager::class);

        $this->prepareUsers();

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $queryClient->getResults($index)['hits']['hits']);
        $this->commandHelper->runCommand(['command' => 'fhplatform:data:sync', 'class-name' => User::class]);
        $this->assertCount(2, $queryClient->getResults($index)['hits']['hits']);
    }

    private function prepareUsers()
    {
        for ($i = 0; $i < 2; ++$i) {
            $user = new User();
            $user->setTestString('test_'.random_int(1, 10000));
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
