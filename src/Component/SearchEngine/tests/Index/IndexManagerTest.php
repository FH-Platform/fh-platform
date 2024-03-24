<?php

namespace FHPlatform\Component\SearchEngine\Tests\Index;

use Elastica\Query;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Manager\ManagerAdapterInterface;
use FHPlatform\Component\SearchEngine\Tests\TestCase;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\User;

class IndexManagerTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var IndexManager $indexManager */
        $indexManager = $this->container->get(IndexManager::class);

        $connection = new Connection('default', 'prefix_', ['servers' => [['host' => 'elasticsearch', 'port' => '9200']]]);

        $indexUser = new Index($connection, '', 'user', 'prefix_user', [], [], []);
        $indexRole = new Index($connection, '', 'role', 'prefix_role', [], [], []);

        $connection->setIndexes([$indexUser, $indexRole]);

        $indexManager->deleteAllIndexesInConnection($connection);
        $this->assertEquals([], $indexManager->getAllIndexesInConnection($connection));

        //create index
        $indexManager->createIndex($indexUser);
        $this->assertEquals([
            'prefix_user',
        ], $indexManager->getAllIndexesInConnection($connection));

        //create index
        $indexManager->createIndex($indexRole);
        $this->assertEquals([
            'prefix_role',
            'prefix_user',
        ], $indexManager->getAllIndexesInConnection($connection));

        //delete index
        $indexManager->deleteIndex($indexUser);
        $this->assertEquals([
            'prefix_role',
        ], $indexManager->getAllIndexesInConnection($connection));

        $indexManager->createIndex($indexUser);
        $indexManager->createIndex($indexRole);
        $this->assertEquals([
            'prefix_role',
            'prefix_user',
        ], $indexManager->getAllIndexesInConnection($connection));

        //delete all
        $indexManager->deleteAllIndexesInConnection($connection);
        $this->assertEquals([], $indexManager->getAllIndexesInConnection($connection));


        $indexManager->createIndex($indexUser);
        $this->assertEquals(0, count($this->getResults($indexUser)));

        /** @var ManagerAdapterInterface $provider */
        $provider = $this->container->get(ManagerAdapterInterface::class);

        $provider->documentsUpdate($indexUser, [new Document($indexUser, 1, ['test' => 1], ChangedEntityDTO::TYPE_CREATE)]);
        $this->assertEquals(0, count($this->getResults($indexUser)));
        $provider->indexRefresh($indexUser);
        $this->assertEquals(1, count($this->getResults($indexUser)));
    }

    private function getResults(Index $index): array
    {
        return $this->queryClient->getResultHits($index, new Query())['hits']['hits'];
    }
}
