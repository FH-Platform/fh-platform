<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntity;

class DecoratorEntity_Default extends DecoratorEntity
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityDataItem(Index $index, mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed
    {
        if ('integer' === $mappingItemType) {
            return $dataItem + 1;
        }

        return $dataItem;
    }
}
