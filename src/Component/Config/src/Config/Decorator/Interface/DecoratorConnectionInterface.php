<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

interface DecoratorConnectionInterface extends DecoratorBaseInterface
{
    public function getConfigAdditional(array $config): array;
}
