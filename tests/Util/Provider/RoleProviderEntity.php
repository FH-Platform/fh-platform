<?php

namespace Util\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;
use Util\Entity\Role;

class RoleProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    /** @param Role $entity */
    public function getEntityData($entity, array $data): array
    {
        $data['id'] = $entity->getId();

        return $data;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];

        return $mapping;
    }
}
