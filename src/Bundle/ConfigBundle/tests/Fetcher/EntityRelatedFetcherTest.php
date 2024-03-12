<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\EntityRelatedFetcher;
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
        $this->assertEquals('DecoratorEntityRelated_First', $entityRelated->getEntitiesRelated()[0]);
        $this->assertEquals('Role', $entityRelated->getEntitiesRelated()[1]);
        $this->assertEquals('DecoratorEntityRelated_Second', $entityRelated->getEntitiesRelated()[2]);
    }
}
