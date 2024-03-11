<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\DecoratorEntity;

class DecoratorEntity_First extends DecoratorEntity
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityData($entity, $data): array
    {
        $data['entity_data_level_1'] = 1;

        return $data;
    }
}
