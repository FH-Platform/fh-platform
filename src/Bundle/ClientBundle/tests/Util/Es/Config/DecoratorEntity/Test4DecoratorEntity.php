<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntity;

class Test4DecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return 2;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $data['data_test4'] = 2;

        return $data;
    }
}
