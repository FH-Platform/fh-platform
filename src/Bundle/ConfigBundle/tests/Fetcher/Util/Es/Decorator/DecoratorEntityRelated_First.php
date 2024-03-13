<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntityRelated;

class DecoratorEntityRelated_First extends DecoratorEntityRelated
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'decorator_entity_related_level_-1';

        return $entitiesRelated;
    }
}
