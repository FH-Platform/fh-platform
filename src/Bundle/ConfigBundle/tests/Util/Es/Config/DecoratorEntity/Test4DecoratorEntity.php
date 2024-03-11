<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\DecoratorEntity;

class Test4DecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return 2;
    }

    public function getEntityData($entity, $data): array
    {
        $data['data_test4'] = 2;

        return $data;
    }
}
