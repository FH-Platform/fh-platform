<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\DecoratorEntity;

class Test3DecoratorEntity extends DecoratorEntity
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityData($entity, $data): array
    {
        $data['data_test3'] = 1;

        return $data;
    }
}
