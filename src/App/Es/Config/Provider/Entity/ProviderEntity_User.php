<?php

namespace App\Es\Config\Provider\Entity;

use App\Entity\User;
use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['name_string'] = $entity->getNameString();

        return $data;
    }

    /** @param User $entity */
    public function getEntityShouldBeIndexed(Index $index, $entity, bool $shouldBeIndexed): bool
    {
        if (0 == $entity->getId() % 2) {
            return false;
        }

        return true;
    }
}
