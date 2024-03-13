<?php

namespace FHPlatform\ClientBundle\Tests\Data;

use Elastica\Query;
use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Tests\TestCase;
use FHPlatform\ClientBundle\Tests\Util\Entity\Log;
use FHPlatform\ClientBundle\Tests\Util\Entity\Role;
use FHPlatform\ClientBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\DTO\Index;

class DataClientTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var DataClient $dataClient */
        $dataClient = $this->container->get(DataClient::class);

        /** @var IndexClient $indexClientNew */
        $indexClientNew = $this->container->get(IndexClient::class);

        $connection = new Connection('default', 'prefix_', ['servers' => [['host' => 'elasticsearch', 'port' => '9200']]]);
        $connection2 = new Connection('default2', 'prefix2_', ['servers' => [['host' => 'elasticsearch2', 'port' => '9200']]]);

        $indexUser = new Index(User::class, $connection, 'user', [], [], []);
        $indexUser2 = new Index(User::class, $connection2, 'user', [], [], []);
        $indexRole = new Index(Role::class, $connection, 'role', [], [], []);
        $indexLog = new Index(Log::class, $connection, 'log', [], [], []);

        $indexClientNew->recreateIndex($indexUser);
        $indexClientNew->recreateIndex($indexUser2);
        $indexClientNew->recreateIndex($indexRole);
        $indexClientNew->recreateIndex($indexLog);

        $this->assertEquals(0, count($this->queryClient->getResults($indexUser, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexUser2, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexRole, new Query())));
        $this->assertEquals(0, count($this->queryClient->getResults($indexLog, new Query())));

        $this->dataClient->upsertBatch([
            new Entity(new User(), User::class, 1, $indexUser, ['test' => '1'], true),
            new Entity(new User(), User::class, 2, $indexUser, ['test2' => '2'], true),
            new Entity(new User(), Role::class, 3, $indexRole, ['test3' => '3'], true),
            new Entity(new User(), User::class, 4, $indexUser2, ['test4' => '4'], true),
            new Entity(new User(), Log::class, 5, $indexLog, ['test5' => '5'], true),
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
