<?php

namespace FHPlatform\ClientBundle\Tests\Data;

use Elastica\Query;
use FHPlatform\ClientBundle\Client\Data\DataClient;
use FHPlatform\ClientBundle\Client\Index\IndexClientNew;
use FHPlatform\ClientBundle\Tests\TestCase;
use FHPlatform\ClientBundle\Tests\Util\Entity\Role;
use FHPlatform\ClientBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;

class DataClientTest extends TestCase
{
    public function testSomething(): void
    {
        /** @var DataClient $dataClient */
        $dataClient = $this->container->get(DataClient::class);

        $connection = new Connection('default', 'prefix_', [
            'servers' => [
                ['host' => 'elasticsearch', 'port' => '9200'],
            ],
        ]);

        $connection2 = new Connection('default2', 'prefix2_', [
            'servers' => [
                ['host' => 'elasticsearch2', 'port' => '9200'],
            ],
        ]);

        $indexUser = new Index(User::class, $connection, 'user', [], [], []);
        $indexUser2 = new Index(User::class, $connection2, 'user', [], [], []);
        $indexRole = new Index(Role::class, $connection, 'role', [], [], []);

        /** @var IndexClientNew $indexClientNew */
        $indexClientNew = $this->container->get(IndexClientNew::class);

        $indexClientNew->recreateIndex($indexUser);
        $indexClientNew->recreateIndex($indexUser2);
        $indexClientNew->recreateIndex($indexRole);

        $this->assertEquals(0, count($this->queryClient->getResults($indexUser, (new Query()))));

        $user = new User();
        $user->setNameString('test');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->dataClient->upsertBatch([
            new Entity($user, User::class, 1, $indexUser, ['test' => '1'], true),
            new Entity($user, User::class, 2, $indexUser, ['test2' => '2'], true),
            new Entity($user, Role::class, 3, $indexRole, ['test3' => '3'], true),
            new Entity($user, User::class, 4, $indexUser2, ['test4' => '4'], true),
        ]);

        $results = $this->queryClient->getResults($indexUser, (new Query()));
        $this->assertEquals(2, count($results));
        $this->assertEquals([
            'test' => 1
        ], ($results[1]->getSource()));

        $this->assertEquals([
            'test2' => 2
        ], ($results[2]->getSource()));

        $results = $this->queryClient->getResults($indexRole, (new Query()));
        $this->assertEquals(1, count($results));
        $this->assertEquals([
            'test3' => 3
        ], ($results[3]->getSource()));

        $results = $this->queryClient->getResults($indexUser2, (new Query()));
        $this->assertEquals(1, count($results));
        $this->assertEquals([
            'test4' => 4
        ], ($results[4]->getSource()));
    }
}
