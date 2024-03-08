<?php

namespace FHPlatform\ConfigBundle\TagProvider\Index;

use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\EntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\EntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.entity')]
#[AutoconfigureTag('symfony_es.provider.entity_related')]
abstract class ProviderEntity extends ProviderIndex implements EntityInterface, EntityRelatedInterface
{
    public function getEntityData($entity, array $data): array
    {
        return $data;
    }

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        return $shouldBeIndexed;
    }

    public function getEntityRelatedEntities($entity, $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
