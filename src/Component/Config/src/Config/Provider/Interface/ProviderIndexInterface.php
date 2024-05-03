<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;

interface ProviderIndexInterface extends ProviderBaseInterface, DecoratorIndexInterface
{
    public function getIndexName(string $className): string;
}
