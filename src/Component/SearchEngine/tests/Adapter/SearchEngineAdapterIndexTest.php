<?php

namespace FHPlatform\Component\SearchEngine\Tests\Adapter;

use Elastica\Query;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter;
use FHPlatform\Component\SearchEngine\Tests\TestCase;

class SearchEngineAdapterIndexTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var SearchEngineAdapter $adapter */
        $adapter = $this->container->get(SearchEngineAdapter::class);

        // prepare connection and indexes
        $connection = new Connection('default', 'prefix_', ['servers' => [['host' => 'elasticsearch', 'port' => '9200']]]);
        $indexUser = new Index($connection, '', 'user', 'prefix_user', [], [], []);
        $indexRole = new Index($connection, '', 'role', 'prefix_role', [], [], []);
        $connection->setIndexes([$indexUser, $indexRole]);

        // remove all indexes
        $adapter->indexesDeleteAllInConnection($connection);
        $this->assertEquals([], $adapter->indexesGetAllInConnection($connection));

        // create index user
        $adapter->indexCreate($indexUser);
        $this->assertEquals([
            'prefix_user',
        ], $adapter->indexesGetAllInConnection($connection));

        // create index role
        $adapter->indexCreate($indexRole);
        $this->assertEquals([
            'prefix_role',
            'prefix_user',
        ], $adapter->indexesGetAllInConnection($connection));

        // delete index
        $adapter->indexDelete($indexUser);
        $this->assertEquals([
            'prefix_role',
        ], $adapter->indexesGetAllInConnection($connection));

        // create exists
        $adapter->indexCreate($indexUser);
        $adapter->indexCreate($indexUser);
        $adapter->indexCreate($indexRole);
        $this->assertEquals([
            'prefix_role',
            'prefix_user',
        ], $adapter->indexesGetAllInConnection($connection));

        // delete all
        $adapter->indexesDeleteAllInConnection($connection);
        $this->assertEquals([], $adapter->indexesGetAllInConnection($connection));

        // refresh
        $adapter->indexCreate($indexUser);
        $this->assertEquals(0, count($this->getResults($indexUser)));
        $adapter->documentsUpdate($indexUser, [new Document($indexUser, 1, ['test' => 1], ChangedEntityDTO::TYPE_CREATE)]);
        $this->assertEquals(0, count($this->getResults($indexUser)));
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(1, count($this->getResults($indexUser)));
    }

    private function getResults(Index $index): array
    {
        return $this->queryClient->getResults($index, new Query())['hits']['hits'];
    }
}
