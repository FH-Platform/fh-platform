<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Tag\Util\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\DecoratorEntity;
use FHPlatform\ConfigBundle\DTO\Index;

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
