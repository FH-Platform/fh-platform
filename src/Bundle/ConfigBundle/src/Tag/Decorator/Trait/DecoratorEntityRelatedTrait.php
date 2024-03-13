<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Trait;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
