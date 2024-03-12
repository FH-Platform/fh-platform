<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_First;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_Second;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\TestProviderEntity;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class IndexFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            TestProviderEntity::class,
            DecoratorIndex_First::class,
            DecoratorIndex_Second::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        /** @var IndexFetcher $indexFetcher */
        $indexFetcher = $this->container->get(IndexFetcher::class);

        // index fetcher
        $index = $indexFetcher->fetch(User::class);
        $this->assertEquals(User::class, $index->getClassName());
        $this->assertEquals('default', $index->getConnection()->getName());
        $this->assertEquals('user', $index->getName());
        $this->assertEquals([
            'index_mapping_level_-1' => -1,
            'index_mapping_level_0' => 0,
            'index_mapping_level_1' => 1,
        ], $index->getMapping());
        $this->assertEquals([
            'index_settings_level_-1' => -1,
            'index_settings_level_0' => 0,
            'index_settings_level_1' => 1,
        ], $index->getSettings());
    }
}
