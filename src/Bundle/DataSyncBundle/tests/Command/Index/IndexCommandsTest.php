<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Index;

use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\Test2ProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\TestProviderEntity;

class IndexCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
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

        /** @var ConnectionsBuilder $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsBuilder::class);
        $connections = $connectionsFetcher->build();
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
