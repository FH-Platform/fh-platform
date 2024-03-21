<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default2;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_First;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_Second;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntityRelated_First;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntityRelated_Second;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_First;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorIndex_Second;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_Company;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntity_User;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Permission;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider\ProviderEntityRelated_Role;

class TestCase extends \FHPlatform\ConfigSymfonyBundle\Tests\TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
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
