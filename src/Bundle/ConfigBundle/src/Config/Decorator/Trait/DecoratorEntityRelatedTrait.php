<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
