<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\FilterToEsDsl\FilterQuery;

class TestCase extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected FilterQuery $filterQuery;
    protected ConnectionsBuilder $connectionsBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var FilterQuery $filterQuery */
        $filterQuery = $this->container->get(FilterQuery::class);
        $this->filterQuery = $filterQuery;

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $this->connectionsBuilder = $connectionsBuilder;
    }

    protected function urlToArray($url): array
    {
        $array = [];

        parse_str($url, $array);

        return $array;
    }
}
