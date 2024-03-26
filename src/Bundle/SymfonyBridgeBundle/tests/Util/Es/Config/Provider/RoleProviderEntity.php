<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\Entity\Role;
use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Index;

class RoleProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    /** @param Role $entity */
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['id'] = $entity->getId();
        $data['nameString'] = $entity->getNameString();
        $data['nameString2'] = '1111';

        return $data;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['nameString'] = ['type' => 'text'];
        $mapping['nameString2'] = ['type' => 'text'];

        return $mapping;
    }

    /** @param Role $entity */
    public function getEntityRelatedEntities(mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
