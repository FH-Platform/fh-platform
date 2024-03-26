<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Es;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;

class TestCaseEs extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected DocumentBuilder $documentBuilder;
    protected ConnectionsBuilder $connectionsBuilder;
    protected EntitiesRelatedBuilder $entitiesRelatedBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var DocumentBuilder $documentBuilder */
        $documentBuilder = $this->container->get(DocumentBuilder::class);
        $this->documentBuilder = $documentBuilder;

        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $this->connectionsBuilder = $connectionsBuilder;

        /** @var EntitiesRelatedBuilder $entitiesRelatedBuilder */
        $entitiesRelatedBuilder = $this->container->get(EntitiesRelatedBuilder::class);
        $this->entitiesRelatedBuilder = $entitiesRelatedBuilder;
    }
}
