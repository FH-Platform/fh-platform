<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Role;

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
