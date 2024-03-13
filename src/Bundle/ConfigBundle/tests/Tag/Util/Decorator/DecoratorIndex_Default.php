<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator;

use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class DecoratorIndex_Default extends DecoratorIndex
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMappingItem(string $className, array $mappingItem, string $key, ?string $type): array
    {
        if ('text' === $type) {
            $mappingItem['test'] = '1234';
        }

        return $mappingItem;
    }
}
