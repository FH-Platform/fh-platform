<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Builder\ConnectionsBuilder;
use FHPlatform\ConfigBundle\DTO\Connection;

class ConnectionsFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        /** @var ConnectionsBuilder $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsBuilder::class);

        $connections = $connectionsFetcher->build();

        /** @var Connection $connection */
        $connection = $connections[0];

        /** @var Connection $connection2 */
        $connection2 = $connections[1];

        $this->assertEquals(2, count($connections));

        $this->assertEquals('default', $connection->getName());
        $this->assertEquals('prefix_default_', $connection->getPrefix());
        $this->assertEquals(['test' => 'test'], $connection->getConfigClient());

        $this->assertEquals('default2', $connection2->getName());
        $this->assertEquals('prefix_default2_', $connection2->getPrefix());
        $this->assertEquals(['test2' => 'test2'], $connection2->getConfigClient());

        $this->assertEquals(1, 1);
    }
}