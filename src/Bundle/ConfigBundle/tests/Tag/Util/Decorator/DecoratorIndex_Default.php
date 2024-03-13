<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class DecoratorIndex_Default extends DecoratorIndex
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array
    {
        if ('text' === $mappingItemType) {
            $mappingItem['test'] = '1234';
        }

        return $mappingItem;
    }
}
