<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\Provider;

use FHPlatform\Component\Config\Config\Provider\ProviderEntity;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Role\Role;

class RoleProvider extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    /** @param Role $entity */
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['id'] = $entity->getId();
        $data['testString'] = $entity->gettestString();
        $data['testString2'] = '1111';

        return $data;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['id'] = ['type' => 'int'];
        $mapping['testString'] = ['type' => 'text'];
        $mapping['testString2'] = ['type' => 'text'];

        return $mapping;
    }

    /** @param Role $entity */
    public function getEntityRelatedEntities(Connection $connection, mixed $entity, string $type, array $changedFields, array $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
