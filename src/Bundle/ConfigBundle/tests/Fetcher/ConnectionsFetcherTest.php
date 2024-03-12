<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
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
use FHPlatform\ConfigBundle\Tests\TestCase;

class ConnectionsFetcherTest extends TestCase
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
        /** @var ConnectionsFetcher $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsFetcher::class);

        $connections = $connectionsFetcher->fetch();

        /** @var Connection $connection */
        $connection = $connections[0];

        /** @var Connection $connection2 */
        $connection2 = $connections[1];

        $this->assertEquals(2, count($connections));

        $this->assertEquals('default', $connection->getName());
        $this->assertEquals('prefix_default_', $connection->getPrefix());
        $this->assertEquals(['test' => 'test'], $connection->getElasticaConfig());

        $this->assertEquals('default2', $connection2->getName());
        $this->assertEquals('prefix_default2_', $connection2->getPrefix());
        $this->assertEquals(['test2' => 'test2'], $connection2->getElasticaConfig());

        $this->assertEquals(1, 1);
    }
}
