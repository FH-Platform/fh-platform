<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;

class RoleProvider extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    public function getConfigAdditional(Index $index, array $config): array
    {
        $config['doctrine_to_es'] =
            [
                'users' => [],
            ];

        return $config;
    }
}
