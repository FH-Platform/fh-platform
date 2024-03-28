<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorBaseInterface;

interface ProviderBaseInterface extends DecoratorBaseInterface
{
    public function getClassName(): string;
}
