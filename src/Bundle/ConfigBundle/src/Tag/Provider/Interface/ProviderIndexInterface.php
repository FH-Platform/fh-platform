<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;

interface ProviderIndexInterface extends ProviderBaseInterface, DecoratorIndexInterface
{
    public function getConnection(): string;

    public function getIndexName(string $className): string;
}
