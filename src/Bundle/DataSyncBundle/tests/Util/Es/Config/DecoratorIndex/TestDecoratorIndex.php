<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class TestDecoratorIndex extends DecoratorIndex
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
