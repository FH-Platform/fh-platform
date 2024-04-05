<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Index;

interface DecoratorIndexInterface extends DecoratorBaseInterface
{
    public function getIndexSettings(Index $index, array $settings): array;

    public function getIndexConfigAdditional(Index $index, array $config): array;

    public function getIndexMapping(Index $index, array $mapping): array;

    public function getIndexMappingItem(Index $index, mixed $mappingItem, mixed $mappingItemKey, mixed $mappingItemType): mixed;
}
