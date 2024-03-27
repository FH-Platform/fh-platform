<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\DecoratorEntity;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntity;
use FHPlatform\Component\Config\DTO\Index;

class TestDecorator extends DecoratorEntity
{
    public function priority(): int
    {
        return -2;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['data_test'] = -2;

        return $data;
    }
}
