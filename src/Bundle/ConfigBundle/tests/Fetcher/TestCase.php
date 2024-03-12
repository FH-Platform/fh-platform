<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
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

class TestCase extends \FHPlatform\ConfigBundle\Tests\TestCase
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
}
