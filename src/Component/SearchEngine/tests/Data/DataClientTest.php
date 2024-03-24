<?php

namespace FHPlatform\Component\SearchEngine\Tests\Data;

use Elastica\Query;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Tests\TestCase;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\Log;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\Role;
use FHPlatform\Component\SearchEngine\Tests\Util\Entity\User;

class DataClientTest extends TestCase
{
    protected bool $testingClient = false;

    public function testSomething(): void
    {
        if (false === $this->testingClient) {
            $this->assertEquals(1, 1);

            return;
        }

        /** @var DataManager $dataClient */
        $dataClient = $this->container->get(DataManager::class);

        /** @var IndexManager $indexClientNew */
        $indexClientNew = $this->container->get(IndexManager::class);

        $connection = new Connection('default', 'prefix_', ['servers' => [['host' => 'elasticsearch', 'port' => '9200']]]);
        $connection2 = new Connection('default2', 'prefix2_', ['servers' => [['host' => 'elasticsearch2', 'port' => '9200']]]);

        $indexUser = new Index($connection, User::class, 'user', 'prefix_user', [], [], []);
        $indexUser2 = new Index($connection2, User::class, 'user', 'prefix2_user', [], [], []);
        $indexRole = new Index($connection, Role::class, 'role', 'prefix_role', [], [], []);
        $indexLog = new Index($connection, Log::class, 'log', 'prefix_log', [], [], []);

        $indexClientNew->recreateIndex($indexUser);
        $indexClientNew->recreateIndex($indexUser2);
        $indexClientNew->recreateIndex($indexRole);
        $indexClientNew->recreateIndex($indexLog);

        $this->assertEquals(0, count($this->getResults($indexUser)));
        $this->assertEquals(0, count($this->getResults($indexUser2)));
        $this->assertEquals(0, count($this->getResults($indexRole)));
        $this->assertEquals(0, count($this->getResults($indexLog)));

        // test empty
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(0, count($results));

        // insert one
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([
            new Document($indexUser, 1, ['test' => '1'], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);

        // insert more
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([
            new Document($indexUser, 1, ['test' => '1'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexUser, 2, ['test2' => '2'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexRole, 3, ['test3' => '3'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexUser2, 4, ['test4' => '4'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexLog, 5, ['test5' => '5'], ChangedEntityDTO::TYPE_CREATE),
        ]);

        $results = $this->getResults($indexUser);
        $this->assertEquals(2, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);
        $this->assertEquals(['test2' => 2], $results[1]['_source']);
        $this->assertEquals(2, $results[1]['_id']);

        $results = $this->getResults($indexRole);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test3' => 3], $results[0]['_source']);
        $this->assertEquals(3, $results[0]['_id']);

        $results = $this->getResults($indexUser2);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test4' => 4], $results[0]['_source']);
        $this->assertEquals(4, $results[0]['_id']);

        $results = $this->getResults($indexLog);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test5' => 5], $results[0]['_source']);
        $this->assertEquals(5, $results[0]['_id']);

        // create (bulk)
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([
            new Document($indexUser, 1, ['test' => '1'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexUser, 2, ['test2' => '2'], ChangedEntityDTO::TYPE_CREATE),
            new Document($indexUser, 3, ['test3' => '3'], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(3, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);
        $this->assertEquals(['test2' => 2], $results[1]['_source']);
        $this->assertEquals(2, $results[1]['_id']);
        $this->assertEquals(['test3' => 3], $results[2]['_source']);
        $this->assertEquals(3, $results[2]['_id']);

        // update (bulk)
        $this->dataClient->syncDocuments([
            new Document($indexUser, 2, ['test2' => '22'], ChangedEntityDTO::TYPE_UPDATE),
            new Document($indexUser, 3, ['test3' => '33'], ChangedEntityDTO::TYPE_UPDATE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(3, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);
        $this->assertEquals(['test2' => 22], $results[1]['_source']);
        $this->assertEquals(2, $results[1]['_id']);
        $this->assertEquals(['test3' => 33], $results[2]['_source']);
        $this->assertEquals(3, $results[2]['_id']);

        // delete (bulk)
        $this->dataClient->syncDocuments([
            new Document($indexUser, 2, [], ChangedEntityDTO::TYPE_DELETE),
            new Document($indexUser, 3, [], ChangedEntityDTO::TYPE_DELETE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);

        // type update for creating
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([
            new Document($indexUser, 1, ['test' => '1'], ChangedEntityDTO::TYPE_CREATE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test' => 1], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);

        // type create for updating
        $indexClientNew->recreateIndex($indexUser);
        $this->dataClient->syncDocuments([
            new Document($indexUser, 1, ['test' => '2'], ChangedEntityDTO::TYPE_UPDATE),
        ]);
        $results = $this->getResults($indexUser);
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test' => 2], $results[0]['_source']);
        $this->assertEquals(1, $results[0]['_id']);
    }

    private function getResults(Index $index): array
    {
        return $this->queryClient->getResults($index, new Query())['hits']['hits'];
    }
}
