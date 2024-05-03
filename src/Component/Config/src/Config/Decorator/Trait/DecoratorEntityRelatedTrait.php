<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Connection;

trait DecoratorEntityRelatedTrait
{
    use DecoratorBaseTrait;

    public function getEntityRelatedEntities(Connection $connection, mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
