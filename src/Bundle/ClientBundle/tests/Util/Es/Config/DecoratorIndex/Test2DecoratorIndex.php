<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\DecoratorIndex;

class Test2DecoratorIndex extends DecoratorIndex
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
