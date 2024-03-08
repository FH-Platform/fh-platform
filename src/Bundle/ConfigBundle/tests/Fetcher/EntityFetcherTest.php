<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\EntityFetcher;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator\DecoratorEntity2;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider\TestProviderEntity;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefault;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class EntityFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefault::class,
            TestProviderEntity::class,
            DecoratorEntity::class,
            DecoratorEntity2::class,
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
