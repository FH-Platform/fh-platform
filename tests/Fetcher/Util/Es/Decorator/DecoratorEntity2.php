<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;

class DecoratorEntity2 extends EntityDecorator
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData($entity, $data): array
    {
        $data['entity_data_level_-1'] = -1;

        return $data;
    }
}
