<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Company;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;

class EntityFetcherTest extends TestCase
{
    public function testFetchEntity(): void
    {
        /** @var EntityFetcher $entityFetcher */
        $entityFetcher = $this->container->get(EntityFetcher::class);

        // entity fetcher
        $user = new User();
        $entity = $entityFetcher->fetch($user);

        $this->assertEquals($user, $entity->getEntity());
        $this->assertEquals('user', $entity->getIndex()->getName());
        $this->assertEquals(true, $entity->getShouldBeIndexed());
        $this->assertEquals([
            'entity_data_level_-1' => -1,
            'entity_data_level_0_user' => 0,
            'entity_data_level_1' => 1,
        ], $entity->getData());

        $company = new Company();
        $entity = $entityFetcher->fetch($company);

        $this->assertEquals($company, $entity->getEntity());
        $this->assertEquals('company', $entity->getIndex()->getName());
        $this->assertEquals(true, $entity->getShouldBeIndexed());
        $this->assertEquals([
            'entity_data_level_-1' => -1,
            'entity_data_level_0_company' => 0,
            'entity_data_level_1' => 1,
        ], $entity->getData());
    }
}
