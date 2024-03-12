<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\Entity\EntityRelatedFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Role;

class EntityRelatedFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        $role = new Role();

        /** @var EntityRelatedFetcher $entityRelatedFetcher */
        $entityRelatedFetcher = $this->container->get(EntityRelatedFetcher::class);

        $entityRelated = $entityRelatedFetcher->fetch($role);

        $this->assertEquals(3, count($entityRelated->getEntitiesRelated()));
        $this->assertEquals('decorator_entity_related_level_-1', $entityRelated->getEntitiesRelated()[0]);
        $this->assertEquals('Role', $entityRelated->getEntitiesRelated()[1]);
        $this->assertEquals('decorator_entity_related_level_1', $entityRelated->getEntitiesRelated()[2]);
    }
}
