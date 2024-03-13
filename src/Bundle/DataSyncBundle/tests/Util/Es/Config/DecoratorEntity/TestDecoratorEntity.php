<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntity;

class TestDecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return -2;
    }

    public function getEntityData(Index $index, mixed $entity, array $data, array $mapping): array
    {
        $data['data_test'] = -2;

        return $data;
    }
}
