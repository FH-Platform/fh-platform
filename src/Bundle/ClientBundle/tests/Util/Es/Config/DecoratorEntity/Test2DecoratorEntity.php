<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntity;

class Test2DecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData(mixed $entity, array $data): array
    {
        $data['data_test2'] = -1;

        return $data;
    }
}