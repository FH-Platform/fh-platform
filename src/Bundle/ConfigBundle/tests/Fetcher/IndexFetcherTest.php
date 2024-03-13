<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;

class IndexFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        /** @var IndexFetcher $indexFetcher */
        $indexFetcher = $this->container->get(IndexFetcher::class);

        // index fetcher
        $index = $indexFetcher->fetch(User::class);
        $this->assertEquals(User::class, $index->getClassName());
        $this->assertEquals([], $index->getAdditionalConfig());
        $this->assertEquals('default', $index->getConnection()->getName());
        $this->assertEquals('user', $index->getName());
        $this->assertEquals([
            'decorator_index_mapping_level_-1' => [-1],
            'decorator_index_mapping_level_0_user' => [0],
            'decorator_index_mapping_level_1' => [1],
        ], $index->getMapping());
        $this->assertEquals([
            'decorator_index_settings_level_-1' => [-1],
            'decorator_index_settings_level_0_user' => [0],
            'decorator_index_settings_level_1' => [1],
        ], $index->getSettings());

        $index = $indexFetcher->fetch(Company::class);
        $this->assertEquals(Company::class, $index->getClassName());
        $this->assertEquals(['test3' => 'test3'], $index->getAdditionalConfig());
        $this->assertEquals('default2', $index->getConnection()->getName());
        $this->assertEquals('company_test', $index->getName());
        $this->assertEquals([
            'decorator_index_mapping_level_-1' => [-1],
            'decorator_index_mapping_level_0_company' => [0],
            'decorator_index_mapping_level_1' => [1],
        ], $index->getMapping());
        $this->assertEquals([
            'decorator_index_settings_level_-1' => [-1],
            'decorator_index_settings_level_0_company' => [0],
            'decorator_index_settings_level_1' => [1],
        ], $index->getSettings());
    }
}
