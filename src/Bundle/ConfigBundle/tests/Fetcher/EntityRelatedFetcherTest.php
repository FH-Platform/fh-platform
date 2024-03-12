<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\ConfigBundle\Fetcher\EntityRelatedFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Role;
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
use FHPlatform\ConfigBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

class EntityRelatedFetcherTest extends TestCase
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
        $role = new Role();

        /** @var EntityRelatedFetcher $entityRelatedFetcher */
        $entityRelatedFetcher = $this->container->get(EntityRelatedFetcher::class);

        $entityRelated = $entityRelatedFetcher->fetch($role);

        $this->assertEquals(3, count($entityRelated->getEntitiesRelated()));
        $this->assertEquals('DecoratorEntityRelated_First', ($entityRelated->getEntitiesRelated()[0]));
        $this->assertEquals('Role', ($entityRelated->getEntitiesRelated()[1]));
        $this->assertEquals('DecoratorEntityRelated_Second', ($entityRelated->getEntitiesRelated()[2]));
    }
}
