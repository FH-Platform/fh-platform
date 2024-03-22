<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

class Test4DecoratorIndex extends DecoratorIndex
{
    public function priority(): int
    {
        return 2;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['test4'] = 2;

        return $mapping;
    }
}
