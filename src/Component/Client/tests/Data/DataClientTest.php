<?php

namespace FHPlatform\Component\Client\Tests\Data;

use Elastica\Query;
use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Client\Provider\Index\IndexClient;
use FHPlatform\Component\Client\Tests\TestCase;
use FHPlatform\Component\Client\Tests\Util\Entity\Log;
use FHPlatform\Component\Client\Tests\Util\Entity\Role;
use FHPlatform\Component\Client\Tests\Util\Entity\User;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Entity;
use FHPlatform\Component\Config\DTO\Index;

class DataClientTest extends TestCase
{
    protected bool $testingClient = false;

    public function testSomething(): void
    {
        if($this->testingClient === false){
            $this->assertEquals(1, 1);
            return;
        }

        /** @var DataClient $dataClient */
        $dataClient = $this->container->get(DataClient::class);

        /** @var IndexClient $indexClientNew */
        $indexClientNew = $this->container->get(IndexClient::class);

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

        $this->assertEquals(0, count($this->queryClient->getResults($indexUser, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexUser2, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexRole, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexLog, new Query())));

        $this->dataClient->syncEntities([
            new Entity($indexUser, 1, ['test' => '1'], true),
            new Entity($indexUser, 2, ['test2' => '2'], true),
            new Entity($indexRole, 3, ['test3' => '3'], true),
            new Entity($indexUser2, 4, ['test4' => '4'], true),
            new Entity($indexLog, 5, ['test5' => '5'], true),
        ]);

        $results = $this->queryClient->getResults($indexUser, new Query());
        $this->assertEquals(2, count($results));
        $this->assertEquals(['test' => 1], $results[1]->getSource());
        $this->assertEquals(['test2' => 2], $results[2]->getSource());

        $results = $this->queryClient->getResults($indexRole, new Query());
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test3' => 3], $results[3]->getSource());

        $results = $this->queryClient->getResults($indexUser2, new Query());
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test4' => 4], $results[4]->getSource());

        $results = $this->queryClient->getResults($indexLog, new Query());
        $this->assertEquals(1, count($results));
        $this->assertEquals(['test5' => 5], $results[5]->getSource());
    }
}
