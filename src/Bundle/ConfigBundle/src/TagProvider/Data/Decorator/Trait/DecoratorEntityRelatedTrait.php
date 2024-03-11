<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
