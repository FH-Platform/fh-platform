<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class TestDecoratorIndex extends DecoratorIndex
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
