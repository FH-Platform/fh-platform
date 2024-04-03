<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\FilterToEsDsl\Query\SearchClassName;

class TestCase extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected SearchClassName $search;
    protected ConnectionsBuilder $connectionsBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var SearchClassName $search */
        $search = $this->container->get(SearchClassName::class);
        $this->search = $search;

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $this->connectionsBuilder = $connectionsBuilder;
    }
}
