<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;

class Test4Decorator extends EntityDecorator
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
