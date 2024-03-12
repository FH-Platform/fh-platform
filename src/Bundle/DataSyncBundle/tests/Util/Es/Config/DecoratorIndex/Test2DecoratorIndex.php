<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorIndex;

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