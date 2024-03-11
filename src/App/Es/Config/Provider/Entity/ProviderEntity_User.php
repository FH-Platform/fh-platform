<?php

namespace App\Es\Config\Provider\Entity;

use App\Entity\User;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderIndex;

class ProviderEntity_User extends ProviderEntity
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param User $entity */
    public function getEntityData(mixed $entity, array $data): array
    {
        $data['name_string'] = $entity->getNameString();

        return $data;
    }

    /** @param User $entity */
    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        if($entity->getId() % 2 == 0){
            return false;
        }

        return true;
    }
}
