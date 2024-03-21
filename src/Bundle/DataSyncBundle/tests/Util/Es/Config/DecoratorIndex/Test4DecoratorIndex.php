<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\DecoratorIndex;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

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
