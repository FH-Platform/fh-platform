<?php

namespace FHPlatform\Component\Persistence\Tests\Util\FHPlatform\Config\DecoratorIndex;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

class Test3Decorator extends DecoratorIndex
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
