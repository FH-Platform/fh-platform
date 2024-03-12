<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
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
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Permission;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Role;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;

class EntityFetcherTest extends TestCase
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
        /** @var EntityFetcher $entityFetcher */
        $entityFetcher = $this->container->get(EntityFetcher::class);

        // entity fetcher
        $user = new User();
        $entity = $entityFetcher->fetch($user);

        $this->assertEquals($user, $entity->getEntity());
        $this->assertEquals('user', $entity->getIndex()->getName());
        $this->assertEquals(true, $entity->getShouldBeIndexed());
        $this->assertEquals([
            'entity_data_level_-1' => -1,
            'entity_data_level_0_user' => 0,
            'entity_data_level_1' => 1,
        ], $entity->getData());

        $company = new Company();
        $entity = $entityFetcher->fetch($company);

        $this->assertEquals($company, $entity->getEntity());
        $this->assertEquals('company', $entity->getIndex()->getName());
        $this->assertEquals(true, $entity->getShouldBeIndexed());
        $this->assertEquals([
            'entity_data_level_-1' => -1,
            'entity_data_level_0_company' => 0,
            'entity_data_level_1' => 1,
        ], $entity->getData());
    }
}
