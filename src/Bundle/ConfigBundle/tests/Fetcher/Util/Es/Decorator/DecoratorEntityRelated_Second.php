<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntityRelated;

class DecoratorEntityRelated_Second extends DecoratorEntityRelated
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $entitiesRelated[] = 'decorator_entity_related_level_1';

        return $entitiesRelated;
    }
}
