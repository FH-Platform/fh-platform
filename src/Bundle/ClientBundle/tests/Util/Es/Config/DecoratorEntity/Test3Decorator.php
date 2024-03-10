<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;

class Test3Decorator extends EntityDecorator
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
