<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\Config\Decorator\DecoratorIndex;
use FHPlatform\ConfigBundle\DTO\Index;

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
