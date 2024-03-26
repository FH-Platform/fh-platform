<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
