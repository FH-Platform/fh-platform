<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;

class MappingDecorator extends DecoratorIndex
{
    public function priority(): int
    {
        return -100;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        return $mapping;
    }
}
