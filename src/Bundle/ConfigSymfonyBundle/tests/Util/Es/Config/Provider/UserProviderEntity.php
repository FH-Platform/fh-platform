<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigSymfonyBundle\Tests\Util\Entity\User;

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
        $data['nameString'] = $entity->getNameString();

        $role = $entity->getRoles()->first();
        if ($role) {
            $data['role'] = $role->getNameString();
        }

        return $data;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['nameString'] = ['type' => 'text'];
        $mapping['role'] = ['type' => 'text'];

        return $mapping;
    }
}