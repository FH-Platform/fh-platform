<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default2;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_First;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_Second;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntityRelated_First;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntityRelated_Second;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_First;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_Second;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_Company;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_Related_Role;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Permission;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Role;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityUser;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;

class IndexFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderConnection_Default::class,
            ProviderConnection_Default2::class,
            ProviderEntity_User::class,
            ProviderEntity_Company::class,
            ProviderEntityRelated_Role::class,
            ProviderEntityRelated_Permission::class,
            DecoratorEntity_First::class,
            DecoratorEntity_Second::class,
            DecoratorIndex_First::class,
            DecoratorIndex_Second::class,
            DecoratorEntityRelated_First::class,
            DecoratorEntityRelated_Second::class,
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
            'index_mapping_level_0_user' => 0,
            'index_mapping_level_1' => 1,
        ], $index->getMapping());
        $this->assertEquals([
            'index_settings_level_-1' => -1,
            'index_settings_level_0_user' => 0,
            'index_settings_level_1' => 1,
        ], $index->getSettings());

        $index = $indexFetcher->fetch(Company::class);
        $this->assertEquals(Company::class, $index->getClassName());
        $this->assertEquals('default', $index->getConnection()->getName());
        $this->assertEquals('company', $index->getName());
        $this->assertEquals([
            'index_mapping_level_-1' => -1,
            'index_mapping_level_0_company' => 0,
            'index_mapping_level_1' => 1,
        ], $index->getMapping());
        $this->assertEquals([
            'index_settings_level_-1' => -1,
            'index_settings_level_0_company' => 0,
            'index_settings_level_1' => 1,
        ], $index->getSettings());
    }
}
