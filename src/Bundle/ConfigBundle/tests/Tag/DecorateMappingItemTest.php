<?php

namespace FHPlatform\ConfigBundle\Tests\Tag;

use FHPlatform\ConfigBundle\Fetcher\Entity\EntityFetcher;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Connection\ProviderConnection_Default;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator\DecoratorEntity_Default;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator\DecoratorIndex_Default;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Provider\ProviderEntity_User;
use FHPlatform\ConfigBundle\Tests\TestCase;

class DecorateMappingItemTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderConnection_Default::class,
            ProviderEntity_User::class,
            DecoratorIndex_Default::class,
            DecoratorEntity_Default::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        /** @var ConnectionsFetcher $connectionsFetcher */
        $connectionsFetcher = $this->container->get(ConnectionsFetcher::class);

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
        ], $connectionsFetcher->fetchIndexesByClassName(User::class)[0]->getMapping());

        /** @var EntityFetcher $entityFetcher */
        $entityFetcher = $this->container->get(EntityFetcher::class);

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
        ], $entityFetcher->fetchEntityForUpsert(new User())->getData());
    }
}
