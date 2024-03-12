<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
