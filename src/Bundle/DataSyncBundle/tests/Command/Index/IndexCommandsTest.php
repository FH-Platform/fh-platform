<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\Test2ProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\TestProviderEntity;

class IndexCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            TestProviderEntity::class,
            Test2ProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var IndexClient $indexClient */
        $indexClient = $this->container->get(IndexClient::class);

        /** @var ConnectionsFetcher $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsFetcher::class);
        $connections = $connectionsFetcher->fetchConnections();
        $connection = $connections[0];

        $indexClient->deleteAllIndexesInConnection($connection);

        $this->assertEquals(0, count($indexClient->getAllIndexesInConnection($connection)));
        $this->commandHelper->runCommand(['command' => 'symfony-es:index:create-all']);
        $indexNames = $indexClient->getAllIndexesInConnection($connections[0]);
        $this->assertEquals(2, count($indexNames));
        $this->assertEquals('prefix_test', $indexNames[0]);
        $this->assertEquals('prefix_test2', $indexNames[1]);

        $this->commandHelper->runCommand(['command' => 'symfony-es:index:delete-all']);
        $this->assertEquals(0, count($indexClient->getAllIndexesInConnection($connection)));

        $this->commandHelper->runCommand(['command' => 'symfony-es:index:create-all']);
        $this->assertEquals(2, count($indexClient->getAllIndexesInConnection($connection)));
        $indexClient->createIndex(new Index($connection, '', 'test3', $connection->getPrefix().'test3', []));
        $this->assertEquals(3, count($indexClient->getAllIndexesInConnection($connection)));
        $this->commandHelper->runCommand(['command' => 'symfony-es:index:delete-stale']);
        $this->assertEquals(2, count($indexClient->getAllIndexesInConnection($connection)));
    }
}
