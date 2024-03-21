<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Config\Util\Sorter\Interface\PriorityInterface;

interface DecoratorIndexInterface extends PriorityInterface
{
    public function getIndexSettings(Index $index, array $settings): array;

    public function getIndexMapping(Index $index, array $mapping): array;

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array;
}
