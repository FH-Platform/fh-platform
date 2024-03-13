<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Interface;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.decorator.entity')]
interface DecoratorEntityInterface extends PriorityInterface
{
    public function getEntityData(Index $index, mixed $entity, array $data, array $mapping): array;

    public function getEntityDataItem(Index $index, mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed;

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool;
}
