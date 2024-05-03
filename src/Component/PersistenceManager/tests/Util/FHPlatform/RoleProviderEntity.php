<?php

namespace FHPlatform\Component\PersistenceManager\Tests\Util\FHPlatform;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;

class RoleProviderEntity extends ProviderEntity
{
    public function getIndexClassName(): string
    {
        return Role::class;
    }

    public function getIndexConfigAdditional(Index $index, array $config): array
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
