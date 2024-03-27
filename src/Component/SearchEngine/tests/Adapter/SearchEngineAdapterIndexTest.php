<?php

namespace FHPlatform\Component\SearchEngine\Tests\Adapter;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;
use FHPlatform\Component\SearchEngine\Tests\TestCase;

class SearchEngineAdapterIndexTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var SearchEngineInterface $adapter */
        $adapter = $this->container->get(SearchEngineInterface::class);

        // prepare connection and indexes
        // TODO
        $config = [
            'servers' => [
                [
                    'host' => 'elasticsearch', 'port' => '9200',
                    'headers' => [
                        'Authorization' => 'Basic ZWxhc3RpYzplbGFzdGlj',
                    ],
                ],
            ],
        ];
        $connection = new Connection('default', 'prefix_', $config);
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
        $adapter->dataUpdate($indexUser, [new Document($indexUser, 1, ['test' => 1], ChangedEntityDTO::TYPE_CREATE)]);
        $this->assertEquals(1, count($this->getResults($indexUser)));

        // test get and delete by prefix
        $indexUser2 = new Index($connection, '', 'user2', 'prefix2_user2', [], [], []);
        $adapter->indexDelete($indexUser2);
        $this->assertEquals(false, $adapter->indexesGetAllInConnection($connection, false)['prefix2_user2'] ?? false);
        $adapter->indexCreate($indexUser2);

        $this->assertEquals(true, in_array('prefix2_user2', $adapter->indexesGetAllInConnection($connection, false)));
        $this->assertEquals(true, in_array('prefix_user', $adapter->indexesGetAllInConnection($connection, false)));

        $this->assertEquals([
            'prefix_user',
        ], $adapter->indexesGetAllInConnection($connection));

        $adapter->indexesDeleteAllInConnection($connection);
        $this->assertEquals(true, in_array('prefix2_user2', $adapter->indexesGetAllInConnection($connection, false)));
        $this->assertEquals(false, in_array('prefix_user', $adapter->indexesGetAllInConnection($connection, false)));
    }

    private function getResults(Index $index): array
    {
        return $this->queryClient->getResults($index, null, 10, 0, QueryManager::TYPE_RAW_SOURCE);
    }
}
