<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Builder\EntitiesRelated;

use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\TestCase;

class TestCaseEntitiesRelated extends TestCase
{
    protected EntitiesRelatedBuilder $entitiesRelatedBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var EntitiesRelatedBuilder $entitiesRelatedBuilder */
        $entitiesRelatedBuilder = $this->container->get(EntitiesRelatedBuilder::class);
        $this->entitiesRelatedBuilder = $entitiesRelatedBuilder;
    }
}
