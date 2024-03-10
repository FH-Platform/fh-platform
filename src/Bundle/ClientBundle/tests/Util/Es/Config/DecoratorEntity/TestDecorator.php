<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorEntity;

use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;

class TestDecorator extends EntityDecorator
{
    public function priority(): int
    {
        return -2;
    }

    public function getEntityData($entity, $data): array
    {
        $data['data_test'] = -2;

        return $data;
    }
}
