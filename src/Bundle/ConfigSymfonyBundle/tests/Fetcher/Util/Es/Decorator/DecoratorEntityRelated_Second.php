<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\DecoratorEntityRelated;

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
