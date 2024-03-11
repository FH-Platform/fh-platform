<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Entity\Role;

class RoleProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    /** @param Role $entity */
    public function getEntityData(mixed $entity, array $data): array
    {
        $data['id'] = $entity->getId();
        $data['nameString'] = $entity->getNameString();
        $data['nameString2'] = '1111';

        return $data;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['nameString'] = ['type' => 'text'];
        $mapping['nameString2'] = ['type' => 'text'];

        return $mapping;
    }

    /** @param Role $entity */
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
