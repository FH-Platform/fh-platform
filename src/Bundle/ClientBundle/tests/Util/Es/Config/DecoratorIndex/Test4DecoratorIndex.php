<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorIndex;

class Test4DecoratorIndex extends DecoratorIndex
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
