<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Config\Decorator\DecoratorEntity;

class DecoratorEntity_Second extends DecoratorEntity
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['decorator_entity_data_level_-1'] = -1;

        return $data;
    }
}
