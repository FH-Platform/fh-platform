<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\UpdatingMap;

use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;

class TestCaseUpdatingMap extends TestCase
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
