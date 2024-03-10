<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\TagProvider\Decorator\IndexDecorator;

class Test2Decorator extends IndexDecorator
{
    public function priority(): int
    {
        return -1;
    }

    public function getIndexMapping(string $className, $mapping): array
    {
        $mapping['test2'] = -1;

        return $mapping;
    }
}
