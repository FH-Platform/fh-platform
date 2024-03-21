<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\Permission;

class ProviderEntityRelated_Permission extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return Permission::class;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'Permission';

        return $entitiesRelated;
    }
}
