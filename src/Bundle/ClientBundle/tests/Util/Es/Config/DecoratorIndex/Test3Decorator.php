<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\TagProvider\Decorator\IndexDecorator;

class Test3Decorator extends IndexDecorator
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMapping(string $className, $mapping): array
    {
        $mapping['test3'] = 1;

        return $mapping;
    }
}
