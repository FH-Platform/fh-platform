<?php

namespace FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

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
