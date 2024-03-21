<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

class Test2DecoratorIndex extends DecoratorIndex
{
    public function priority(): int
    {
        return -1;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['test2'] = -1;

        return $mapping;
    }
}
