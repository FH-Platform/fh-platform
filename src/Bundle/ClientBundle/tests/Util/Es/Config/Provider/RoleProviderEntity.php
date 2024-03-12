<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ClientBundle\Tests\Util\Entity\Role;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;

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
}
