<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Index;

interface DecoratorIndexInterface extends DecoratorBaseInterface
{
    public function getIndexSettings(Index $index, array $settings): array;

    public function getIndexMapping(Index $index, array $mapping): array;

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array;
}
