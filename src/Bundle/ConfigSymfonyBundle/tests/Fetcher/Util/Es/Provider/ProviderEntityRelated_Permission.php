<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Provider;

use FHPlatform\ConfigBundle\Config\Provider\ProviderEntityRelated;
use FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Entity\Permission;

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
