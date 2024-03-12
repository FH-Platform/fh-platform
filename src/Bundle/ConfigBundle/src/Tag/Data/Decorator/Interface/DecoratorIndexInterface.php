<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.decorator.index')]
interface DecoratorIndexInterface extends PriorityInterface
{
    public function getConnection(): string;

    public function getIndexName(string $className, string $name): string;

    public function getIndexSettings(string $className, array $settings): array;

    public function getIndexMapping(string $className, array $mapping): array;
}
