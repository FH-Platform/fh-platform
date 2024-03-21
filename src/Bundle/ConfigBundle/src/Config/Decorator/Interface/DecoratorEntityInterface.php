<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface;

use FHPlatform\Bundle\ConfigBundle\DTO\Index;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface DecoratorEntityInterface extends PriorityInterface
{
    public function getEntityData(Index $index, mixed $entity, array $data): array;

    public function getEntityDataItem(Index $index, mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed;

    public function getEntityShouldBeIndexed(Index $index, $entity, bool $shouldBeIndexed): bool;
}
