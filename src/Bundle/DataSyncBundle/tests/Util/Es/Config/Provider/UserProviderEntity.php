<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;

class UserProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        $data['id'] = $entity->getId();
        $data['nameString'] = $entity->getNameString();

        $role = $entity->getRoles()->first();
        if ($role) {
            $data['role'] = $role->getNameString();
        }

        return $data;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['nameString'] = ['type' => 'text'];
        $mapping['role'] = ['type' => 'text'];

        return $mapping;
    }
}
