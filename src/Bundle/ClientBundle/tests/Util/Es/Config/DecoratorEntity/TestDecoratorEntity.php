<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntity;

class TestDecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return -2;
    }

    public function getEntityData(mixed $entity, array $data): array
    {
        $data['data_test'] = -2;

        return $data;
    }
}
