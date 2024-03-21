<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Entity\User;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        return [
            'test_text' => ['type' => 'text'],
            'test_integer' => ['type' => 'integer'],
            'test_object' => [
                'type' => 'object',
                'properties' => [
                    'test_text' => ['type' => 'text'],
                    'test_integer' => ['type' => 'integer'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
            'test_nested' => [
                'type' => 'nested',
                'properties' => [
                    'test_text' => ['type' => 'text'],
                    'test_integer' => ['type' => 'integer'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                            'test_integer' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        return [
            'test_text' => 'test',
            'test_integer' => 1,
            'test_object' => [
                'test_text' => 'test',
                'test_integer' => 1,
                'test_object' => [
                    'test_text' => 'test',
                    'test_integer' => 1,
                ],
                'test_nested' => [
                    [
                        'test_text' => 'test',
                        'test_integer' => 1,
                    ],
                    [
                        'test_text' => 'test',
                        'test_integer' => 1,
                    ],
                ],
            ],
            'test_nested' => [
                [
                    'test_text' => 'test',
                    'test_integer' => 1,
                    'test_object' => [
                        'test_text' => 'test',
                        'test_integer' => 1,
                    ],
                    'test_nested' => [
                        [
                            'test_text' => 'test',
                            'test_integer' => 1,
                        ],
                        [
                            'test_text' => 'test',
                            'test_integer' => 1,
                        ],
                    ],
                ],
            ],
        ];
    }
}
