<?php

namespace FHPlatform\ConfigBundle\Tests\Tag\Util\Decorator;

use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntity;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class DecoratorEntity_Default extends DecoratorEntity
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityDataItem(mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed{
        if ('integer' === $mappingItemType) {
            return $dataItem + 1;
        }

        return $dataItem;
    }
}
