<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_First;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity_Second;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\TestProviderEntity;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class EntityFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            TestProviderEntity::class,
            DecoratorEntity_First::class,
            DecoratorEntity_Second::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        /** @var EntityFetcher $entityFetcher */
        $entityFetcher = $this->container->get(EntityFetcher::class);

        // entity fetcher
        $user = new User();
        $entity = $entityFetcher->fetch($user);
        $this->assertEquals($user, $entity->getEntity());
        $this->assertEquals(true, $entity->getShouldBeIndexed());
        $this->assertEquals([
            'entity_data_level_-1' => -1,
            'entity_data_level_0' => 0,
            'entity_data_level_1' => 1,
        ], $entity->getData());
    }
}
