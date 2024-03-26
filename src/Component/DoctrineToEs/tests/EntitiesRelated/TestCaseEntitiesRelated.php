<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\EntitiesRelated;

use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;

class TestCaseEntitiesRelated extends \FHPlatform\Bundle\TestsBundle\Tests\TestCase
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
