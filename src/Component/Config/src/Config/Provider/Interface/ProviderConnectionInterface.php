<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;

interface ProviderConnectionInterface extends ProviderBaseInterface, DecoratorConnectionInterface
{
    public function getName(): string;

    public function getIndexPrefix(): string;

    public function getClientConfig(): array;
}
