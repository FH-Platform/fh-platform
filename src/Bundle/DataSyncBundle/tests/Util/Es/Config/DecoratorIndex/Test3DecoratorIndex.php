<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\DecoratorIndex;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

class Test3DecoratorIndex extends DecoratorIndex
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['test3'] = 1;

        return $mapping;
    }
}
