<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Tag;

use FHPlatform\ConfigBundle\Builder\EntityBuilder;
use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Provider\ConnectionsProvider;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default;
use FHPlatform\ConfigSymfonyBundle\Tests\Tag\Util\Decorator\DecoratorEntity_Default;
use FHPlatform\ConfigSymfonyBundle\Tests\Tag\Util\Decorator\DecoratorIndex_Default;
use FHPlatform\ConfigSymfonyBundle\Tests\Tag\Util\Entity\User;
use FHPlatform\ConfigSymfonyBundle\Tests\Tag\Util\Provider\ProviderEntity_User;
use FHPlatform\ConfigSymfonyBundle\Tests\TestCase;

class DecorateMappingItemTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderConnection_Default::class,
            ProviderEntity_User::class,
            DecoratorIndex_Default::class,
            DecoratorEntity_Default::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        /** @var ConnectionsProvider $connectionsProvider */
        $connectionsProvider = $this->container->get(ConnectionsProvider::class);

        $this->assertEquals([
            'test_text' => ['type' => 'text', 'test' => '1234'],
            'test_integer' => ['type' => 'integer'],
            'test_object' => [
                'type' => 'object',
                'properties' => [
                    'test_text' => ['type' => 'text', 'test' => '1234'],
                    'test_integer' => ['type' => 'integer'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text', 'test' => '1234'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text', 'test' => '1234'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
            'test_nested' => [
                'type' => 'nested',
                'properties' => [
                    'test_text' => ['type' => 'text', 'test' => '1234'],
                    'test_integer' => ['type' => 'integer'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text', 'test' => '1234'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text', 'test' => '1234'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ], $connectionsProvider->fetchIndexesByClassName(User::class)[0]->getMapping());

        /** @var EntityBuilder $entityFetcher */
        $entityFetcher = $this->container->get(EntityBuilder::class);

        $this->assertEquals([
            'test_text' => 'test',
            'test_integer' => 2,
            'test_object' => [
                'test_text' => 'test',
                'test_integer' => 2,
                'test_object' => [
                    'test_text' => 'test',
                    'test_integer' => 2,
                ],
                'test_nested' => [
                    [
                        'test_text' => 'test',
                        'test_integer' => 2,
                    ],
                    [
                        'test_text' => 'test',
                        'test_integer' => 2,
                    ],
                ],
            ],
            'test_nested' => [
                [
                    'test_text' => 'test',
                    'test_integer' => 2,
                    'test_object' => [
                        'test_text' => 'test',
                        'test_integer' => 2,
                    ],
                    'test_nested' => [
                        [
                            'test_text' => 'test',
                            'test_integer' => 2,
                        ],
                        [
                            'test_text' => 'test',
                            'test_integer' => 2,
                        ],
                    ],
                ],
            ],
        ], $entityFetcher->buildForUpsert(new User())->getData());
    }
}
