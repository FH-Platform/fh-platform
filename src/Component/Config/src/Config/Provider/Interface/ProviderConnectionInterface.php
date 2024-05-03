<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorBaseInterface;

interface ProviderConnectionInterface extends DecoratorBaseInterface
{
    public function getName(): string;

    public function getIndexPrefix(): string;

    public function getClientConfig(): array;
}
