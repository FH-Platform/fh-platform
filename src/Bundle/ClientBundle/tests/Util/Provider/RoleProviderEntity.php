<?php

namespace FHPlatform\ClientBundle\Tests\Util\Config\Provider;

use FHPlatform\ClientBundle\Tests\Util\Entity\Role;
use FHPlatform\ConfigBundle\Tag\Provider\ProviderEntity;

class RoleProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return Role::class;
    }

    /** @param Role $entity */
    public function getEntityData(mixed $entity, array $data, array $mapping): array
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
