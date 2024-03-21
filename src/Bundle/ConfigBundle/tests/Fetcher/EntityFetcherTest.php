<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Builder\EntityBuilder;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;

class EntityFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        /** @var EntityBuilder $entityFetcher */
        $entityFetcher = $this->container->get(EntityBuilder::class);

        // entity fetcher
        $user = new User();
        $entity = $entityFetcher->buildForUpsert($user);

        $this->assertEquals('user', $entity->getIndex()->getName());
        $this->assertEquals(true, $entity->getUpsert());
        $this->assertEquals([
            'decorator_entity_data_level_-1' => -1,
            'entity_data_level_0_user' => 0,
            'decorator_entity_data_level_1' => 1,
        ], $entity->getData());

        $company = new Company();
        $entity = $entityFetcher->buildForUpsert($company);

        $this->assertEquals(false, $entity->getUpsert());
        $this->assertEquals('company_test', $entity->getIndex()->getName());
        $this->assertEquals([
            'decorator_entity_data_level_-1' => -1,
            'entity_data_level_0_company' => 0,
            'decorator_entity_data_level_1' => 1,
        ], $entity->getData());
    }
}
