<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Entity\Company;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Entity\User;

class IndexFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);

        // index fetcher
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];
        $this->assertEquals(User::class, $index->getClassName());
        $this->assertEquals([], $index->getConfigAdditional());
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

        $index = $connectionsBuilder->fetchIndexesByClassName(Company::class)[0];
        $this->assertEquals(Company::class, $index->getClassName());
        $this->assertEquals(['test3' => 'test3'], $index->getConfigAdditional());
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
