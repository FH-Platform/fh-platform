<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\TagProvider\Decorator\IndexDecorator;

class Test4Decorator extends IndexDecorator
{
    public function priority(): int
    {
        return 2;
    }

    public function getIndexMapping(string $className, $mapping): array
    {
        $mapping['test4'] = 2;

        return $mapping;
    }
}
