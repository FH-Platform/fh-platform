<?php

namespace App\Es\Config\Provider\EntityRelated;

use App\Entity\Role;
use App\Entity\User;
use FHPlatform\Component\Config\Config\Provider\ProviderEntityRelated;

class ProviderEntityRelated_Role extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return User::class;
    }

    /** @param Role $entity */
    public function getEntityRelatedEntities(mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
