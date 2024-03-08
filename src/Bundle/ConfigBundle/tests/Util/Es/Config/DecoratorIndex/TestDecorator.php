<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\TagProvider\Decorator\IndexDecorator;

class TestDecorator extends IndexDecorator
{
    public function priority(): int
    {
        return -2;
    }

    public function getIndexMapping(string $className, $mapping): array
    {
        $mapping['test'] = -2;

        return $mapping;
    }
}
