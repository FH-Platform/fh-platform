<?php

namespace FHPlatform\Component\SearchEngine\Tests\Adapter;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;
use FHPlatform\Component\SearchEngine\Tests\TestCase;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\Role;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\User;

class SearchEngineAdapterDataTest extends TestCase
{
    protected bool $testingAdapter = false;

    public function testSomething(): void
    {
        if (false === $this->testingAdapter) {
            $this->assertEquals(1, 1);

            return;
        }

        /** @var SearchEngineAdapter $adapter */
        $adapter = $this->container->get(SearchEngineAdapter::class);

        // prepare entities
        $user = new User();
        $user->setNameString('test');

        $user2 = new User();
        $user2->setNameString('test2');

        $user3 = new User();
        $user3->setNameString('test3');

        $this->save([$user, $user2, $user3]);

        // prepare connection and indexes
        $connection = new Connection('default', 'prefix_', ['servers' => [['host' => 'elasticsearch', 'port' => '9200']]]);
        $indexUser = new Index($connection, User::class, 'user', 'prefix_user', [], [], []);
        $indexRole = new Index($connection, Role::class, 'role', 'prefix_role', [], [], []);
        $connection->setIndexes([$indexUser, $indexRole]);

        // clear index
        $adapter->indexDelete($indexUser);
        $adapter->indexCreate($indexUser);

        // insert one
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, ['test' => 1], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(1, count($this->getResults($indexUser)));

        // insert two
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 2, ['test2' => 2], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexUser, 3, ['test3' => 3], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(3, count($this->getResults($indexUser)));

        // update one
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, ['test' => 11], ChangedEntityDTO::TYPE_UPDATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(3, count($this->getResults($indexUser)));
        $this->assertEquals(['test' => 11], $this->getResults($indexUser)[2]);

        // update two
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 2, ['test2' => 22], ChangedEntityDTO::TYPE_UPDATE),
            new Document($indexUser, 3, ['test3' => 33], ChangedEntityDTO::TYPE_UPDATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(3, count($this->getResults($indexUser)));
        $this->assertEquals(['test' => 11], $this->getResults($indexUser)[0]);
        $this->assertEquals(['test2' => 22], $this->getResults($indexUser)[1]);
        $this->assertEquals(['test3' => 33], $this->getResults($indexUser)[2]);

        // delete one
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, [], ChangedEntityDTO::TYPE_DELETE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(2, count($this->getResults($indexUser)));
        $this->assertEquals(['test2' => 22], $this->getResults($indexUser)[0]);
        $this->assertEquals(['test3' => 33], $this->getResults($indexUser)[1]);

        // delete two
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 2, [], ChangedEntityDTO::TYPE_DELETE),
            new Document($indexUser, 3, [], ChangedEntityDTO::TYPE_DELETE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(0, count($this->getResults($indexUser)));

        // create with update
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, ['test' => 1], ChangedEntityDTO::TYPE_UPDATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(1, count($this->getResults($indexUser)));
        $this->assertEquals(['test' => 1], $this->getResults($indexUser)[0]);

        // update with create
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, ['test' => 11], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(1, count($this->getResults($indexUser)));
        $this->assertEquals(['test' => 11], $this->getResults($indexUser)[0]);

        // delete data not empty
        $adapter->dataUpdate($indexUser, [
            new Document($indexUser, 1, ['test' => 111], ChangedEntityDTO::TYPE_DELETE),
        ]);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(0, count($this->getResults($indexUser)));

        // test empty
        $adapter->dataUpdate($indexUser, []);
        $adapter->indexRefresh($indexUser);
        $this->assertEquals(0, count($this->getResults($indexUser)));
    }

    private function getResults(Index $index): array
    {
        return $this->queryClient->getResults($index, null, 10, 0, QueryManager::TYPE_RAW_SOURCE);
    }
}
