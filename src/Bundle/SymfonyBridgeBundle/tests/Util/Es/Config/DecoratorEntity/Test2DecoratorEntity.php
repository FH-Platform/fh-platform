<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntity;
use FHPlatform\Component\Config\DTO\Index;

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
