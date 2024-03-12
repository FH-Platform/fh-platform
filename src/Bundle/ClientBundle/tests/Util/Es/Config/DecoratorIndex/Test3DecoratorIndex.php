<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorIndex;

class Test3DecoratorIndex extends DecoratorIndex
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