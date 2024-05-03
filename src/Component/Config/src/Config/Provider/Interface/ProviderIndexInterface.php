<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;

interface ProviderIndexInterface extends ProviderBaseInterface, DecoratorIndexInterface
{
    public function getIndexClassName(): string;

    public function getIndexConnectionName(): string;

    public function getIndexName(string $className): string;
}
