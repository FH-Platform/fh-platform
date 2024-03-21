<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntityRelated;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Entity\Role;

class ProviderEntityRelated_Role extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return Role::class;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'Role';

        return $entitiesRelated;
    }
}
