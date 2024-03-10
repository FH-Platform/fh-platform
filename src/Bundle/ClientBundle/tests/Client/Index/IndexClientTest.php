<?php

namespace Client\Index;

use FHPlatform\ClientBundle\Exception\ClassNameForIndexNotExists;
use FHPlatform\ClientBundle\Tests\TestCase;
use FHPlatform\ClientBundle\Tests\Util\Entity\Role;
use FHPlatform\ClientBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ClientBundle\Tests\Util\Helper\TaggedProviderMock;

class IndexClientTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
            RoleProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        // getIndex
        $index = $this->indexClient->getIndex(Role::class);
        $this->assertEquals('prefix_role', $index->getName());

        try {
            $index = $this->indexClient->getIndex('ClassDummy');
        } catch (ClassNameForIndexNotExists $e) {
            $this->assertEquals(ClassNameForIndexNotExists::class, get_class($e));
        }

        $this->indexClient->deleteIndex(Role::class);
        $index = $this->indexClient->getIndex(Role::class);
        $this->assertEquals(false, $index->exists());

        // createIndex
        $index = $this->indexClient->createIndex(Role::class);
        $this->assertEquals(true, $index->exists());

        // deleteIndex
        $this->indexClient->deleteIndex(Role::class);
        $this->assertEquals(false, $index->exists());

        $index = $this->indexNameClient->createIndexByName('prefix_role', [], ['settings' => [
            'refresh_interval' => '30s',
        ]]);
        $this->assertEquals('30s', $index->getSettings()->getRefreshInterval());

        // recreateIndex
        $this->indexClient->recreateIndex(Role::class);
        $this->assertEquals('1s', $index->getSettings()->getRefreshInterval());

        // getIndex
        $indexName = $this->indexClient->getIndexName(Role::class);
        $this->assertEquals('prefix_role', $indexName);
    }
}
