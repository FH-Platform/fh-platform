<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class UserProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    public function getConfigAdditional(): array
    {
        return [
            'doctrine_to_es' => [
                'testInteger',
                'setting' => [
                    'testFloat',
                ],
            ],
        ];
    }
}