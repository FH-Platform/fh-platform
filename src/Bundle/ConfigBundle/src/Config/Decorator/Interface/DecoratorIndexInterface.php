<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface;

use FHPlatform\Bundle\ConfigBundle\DTO\Index;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface DecoratorIndexInterface extends PriorityInterface
{
    public function getIndexSettings(Index $index, array $settings): array;

    public function getIndexMapping(Index $index, array $mapping): array;

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array;
}
