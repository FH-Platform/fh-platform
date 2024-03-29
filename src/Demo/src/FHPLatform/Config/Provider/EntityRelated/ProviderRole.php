<?php

namespace App\FHPLatform\Config\Provider\EntityRelated;

use App\Entity\Role;
use App\Entity\User;
use FHPlatform\Component\Config\Config\Provider\ProviderEntityRelated;
use FHPlatform\Component\Config\DTO\Connection;

class ProviderRole extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param Role $entity */
    public function getEntityRelatedEntities(Connection $connection, mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
