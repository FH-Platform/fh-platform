<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;

interface ProviderIndexInterface extends ProviderBaseInterface, DecoratorIndexInterface
{
    public function getConnection(): string;

    public function getIndexName(string $className): string;
}
