<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntity;

class DecoratorEntity_Second extends DecoratorEntity
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        $data['decorator_entity_data_level_-1'] = -1;

        return $data;
    }
}
