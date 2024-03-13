<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.decorator.index')]
interface DecoratorIndexInterface extends PriorityInterface
{
    public function getIndexSettings(string $className, array $settings): array;

    public function getIndexMapping(string $className, array $mapping): array;

    public function getIndexMappingItem(string $className, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array;
}
