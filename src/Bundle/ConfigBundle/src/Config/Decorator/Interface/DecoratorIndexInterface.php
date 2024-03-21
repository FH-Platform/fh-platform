<?php

namespace FHPlatform\ConfigBundle\Config\Decorator\Interface;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

interface DecoratorIndexInterface extends PriorityInterface
{
    public function getIndexSettings(Index $index, array $settings): array;

    public function getIndexMapping(Index $index, array $mapping): array;

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array;
}
