<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Provider;


use FHPlatform\ConfigBundle\Tag\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Tag\Util\Entity\User;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        return [
            'test_integer' => ['type' => 'integer'],
            'test_object' => [
                'type' => 'object',
                'properties' => [
                    'test_text' => ['type' => 'text'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                        ],
                    ],
                ],
            ],
            'test_nested' => [
                'type' => 'nested',
                'properties' => [
                    'test_text' => ['type' => 'text'],
                    'test_object' => [
                        'type' => 'object',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                        ],
                    ],
                    'test_nested' => [
                        'type' => 'nested',
                        'properties' => [
                            'test_text' => ['type' => 'text'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
