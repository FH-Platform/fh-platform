<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;
use FHPlatform\ClientBundle\Tests\Util\Entity\Role;

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
    public function getEntityRelatedEntities($entity, $entitiesRelated): array
    {
        return $entity->getUsers()->toArray();
    }
}
