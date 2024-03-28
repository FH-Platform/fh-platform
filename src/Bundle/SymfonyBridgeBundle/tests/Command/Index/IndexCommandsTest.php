<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Command\Index;

use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\TestCase;
use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Connections\ProviderDefault;
use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider\Test2Provider;
use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\Provider\TestProvider;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;

class IndexCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefault::class,
            TestProvider::class,
            Test2Provider::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var IndexManager $indexClient */
        $indexClient = $this->container->get(IndexManager::class);

        /** @var ConnectionsBuilder $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsBuilder::class);
        $connections = $connectionsFetcher->build();
        $connection = $connections[0];

        $indexClient->deleteAllIndexesInConnection($connection);

        $this->assertEquals(0, count($indexClient->getAllIndexesInConnection($connection)));
        $this->commandHelper->runCommand(['command' => 'fhplatform:index:create-all']);
        $indexNames = $indexClient->getAllIndexesInConnection($connections[0]);
        $this->assertEquals(2, count($indexNames));
        $this->assertEquals('prefix_test', $indexNames[0]);
        $this->assertEquals('prefix_test2', $indexNames[1]);

        $this->commandHelper->runCommand(['command' => 'fhplatform:index:delete-all']);
        $this->assertEquals(0, count($indexClient->getAllIndexesInConnection($connection)));

        $this->commandHelper->runCommand(['command' => 'fhplatform:index:create-all']);
        $this->assertEquals(2, count($indexClient->getAllIndexesInConnection($connection)));
        $indexClient->createIndex(new Index($connection, '', false, 'test3', $connection->getPrefix().'test3', []));
        $this->assertEquals(3, count($indexClient->getAllIndexesInConnection($connection)));
        $this->commandHelper->runCommand(['command' => 'fhplatform:index:delete-stale']);
        $this->assertEquals(2, count($indexClient->getAllIndexesInConnection($connection)));
    }
}
