<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntityRelated;

class EntityRelatedDecorator extends DecoratorEntityRelated
{
    public function priority(): int
    {
        return -100;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
