<?php

namespace App\Es\Config\Provider\Entity;

use App\Entity\User;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        $data['name_string'] = $entity->getNameString();

        return $data;
    }

    /** @param User $entity */
    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        if (0 == $entity->getId() % 2) {
            return false;
        }

        return true;
    }
}
