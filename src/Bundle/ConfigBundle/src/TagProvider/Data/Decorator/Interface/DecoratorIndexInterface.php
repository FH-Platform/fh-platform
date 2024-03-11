<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.decorator.index')]
interface DecoratorIndexInterface extends PriorityInterface
{
    public function getIndexName(string $className, string $name): string;

    public function getIndexSettings(string $className, array $settings): array;

    public function getIndexMapping(string $className, array $mapping): array;
}
