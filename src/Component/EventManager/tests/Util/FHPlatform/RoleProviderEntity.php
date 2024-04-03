<?php

namespace FHPlatform\Component\EventManager\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;

class RoleProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    public function getConfigAdditional(Index $index, array $config): array
    {
        $config['doctrine_to_es'] =
            [
                'testString',
                'users' => [
                    'testString',
                ],
            ];

        return $config;
    }
}
