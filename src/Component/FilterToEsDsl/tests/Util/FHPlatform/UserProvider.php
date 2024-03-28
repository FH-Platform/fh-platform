<?php

namespace FHPlatform\Component\FilterToEsDsl\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class UserProvider extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getConfigAdditional(): array
    {
        /*return [
            'doctrine_to_es' => [
                'id',
                'setting' => [
                    'id',
                    'settingGroup' => [
                        'id',
                    ],
                ],
            ],
        ];*/

        return [
            'doctrine_to_es' => [
                'setting' => [
                    'settingGroup' => [],
                    'settingItems' => [],
                ],
                'bills' => [],
            ],
        ];
    }
}