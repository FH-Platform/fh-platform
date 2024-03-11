<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.decorator.index')]
interface IndexInterface
{
    public function getIndexName(string $className, string $name): string;

    public function getIndexSettings(string $className, array $settings): array;

    public function getIndexMapping(string $className, array $mapping): array;
}
