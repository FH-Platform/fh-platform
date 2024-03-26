<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Es;

use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;

class TestCaseEs extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected DocumentBuilder $documentBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var DocumentBuilder $documentBuilder */
        $documentBuilder = $this->container->get(DocumentBuilder::class);
        $this->documentBuilder = $documentBuilder;
    }
}
