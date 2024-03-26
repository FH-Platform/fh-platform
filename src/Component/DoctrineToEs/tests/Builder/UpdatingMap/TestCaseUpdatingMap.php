<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;

class TestCaseUpdatingMap extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
{
    protected UpdatingMapBuilder $updatingMapBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var UpdatingMapBuilder $updatingMapBuilder */
        $updatingMapBuilder = $this->container->get(UpdatingMapBuilder::class);
        $this->updatingMapBuilder = $updatingMapBuilder;
    }
}
