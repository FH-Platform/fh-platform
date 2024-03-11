<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait;

trait EntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
