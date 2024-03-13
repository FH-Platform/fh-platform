<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\DecoratorIndex;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

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
