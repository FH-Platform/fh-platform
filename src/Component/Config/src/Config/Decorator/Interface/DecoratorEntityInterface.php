<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Index;

interface DecoratorEntityInterface extends DecoratorBaseInterface
{
    public function getEntityData(Index $index, mixed $entity, array $data): array;

    public function getEntityDataItem(Index $index, mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed;

    public function getEntityShouldBeIndexed(Index $index, $entity, bool $shouldBeIndexed): bool;
}
