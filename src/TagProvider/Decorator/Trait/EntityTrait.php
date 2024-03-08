<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator\Trait;

trait EntityTrait
{
    public function getEntityData($entity, $data): array
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
