<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ConnectionProviderDefault;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ConnectionProviderDefault2;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class ConnectionsFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ConnectionProviderDefault::class,
            ConnectionProviderDefault2::class,
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
