<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class UserProvider extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['id'] = $entity->getId();
        $data['testString'] = $entity->gettestString();

        $role = $entity->getRoles()->first();
        if ($role) {
            $data['role'] = $role->gettestString();
        }

        return $data;
    }

    public function getConfigAdditional(Index $index, array $config): array
    {
        $config['doctrine_to_es'] =
            [
                'roles' => [],
            ];

        return $config;
    }
}
