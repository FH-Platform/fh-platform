<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Tests\Util\FHPlatform\Config\DecoratorIndex;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

class TestDecorator extends DecoratorIndex
{
    public function priority(): int
    {
        return -2;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['test'] = -2;

        return $mapping;
    }
}
