<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Util\Es\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class UserProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['id'] = $entity->getId();
        $data['name'] = $entity->getName();
        $data['name2'] = $entity->getName2();
        $data['number'] = $entity->getNumber();
        $data['number2'] = $entity->getNumber2();

        $role = $entity->getRoles()->first();
        if ($role) {
            $data['role'] = $role->getNameString();
        }

        return $data;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['name'] = ['type' => 'text'];
        $mapping['name2'] = ['type' => 'text'];
        $mapping['number'] = ['type' => 'int'];
        $mapping['number2'] = ['type' => 'int'];
        $mapping['role'] = ['type' => 'text'];

        return $mapping;
    }
}
