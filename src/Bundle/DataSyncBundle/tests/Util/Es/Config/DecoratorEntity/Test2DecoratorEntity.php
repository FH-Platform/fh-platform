<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\DecoratorEntity;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

class Test2DecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['data_test2'] = -1;

        return $data;
    }
}
