<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;

class Test2Decorator extends EntityDecorator
{
    public function priority(): int
    {
        return -1;
    }

    public function getEntityData($entity, $data): array
    {
        $data['data_test2'] = -1;

        return $data;
    }
}
