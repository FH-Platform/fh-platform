<?php

namespace FHPlatform\DataSyncBundle\Tests\Command\Index;

use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\Test2ProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\TestProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Helper\TaggedProviderMock;

class IndexCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            TestProviderEntity::class,
            Test2ProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $this->indexNameClient->deleteAllIndexesByPrefix();

        $this->assertCount(0, $this->indexNameClient->getIndexesNameByPrefix());
        $this->commandHelper->runCommand(['command' => 'symfony-es:index:create-all']);

        return; // TODO fix
        $this->assertCount(2, $this->indexNameClient->getIndexesNameByPrefix());

        $this->commandHelper->runCommand(['command' => 'symfony-es:index:delete-all']);
        $this->assertCount(0, $this->indexNameClient->getIndexesNameByPrefix());

        $this->commandHelper->runCommand(['command' => 'symfony-es:index:create-all']);
        $this->assertCount(2, $this->indexNameClient->getIndexesNameByPrefix());

        $this->indexNameClient->createIndexByName('prefix_test3');
        $this->assertCount(3, $this->indexNameClient->getIndexesNameByPrefix());

        $this->commandHelper->runCommand(['command' => 'symfony-es:index:delete-stale']);
        $this->assertCount(2, $this->indexNameClient->getIndexesNameByPrefix());
    }
}
