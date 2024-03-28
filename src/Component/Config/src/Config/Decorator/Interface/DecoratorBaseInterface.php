<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

interface DecoratorBaseInterface
{
    public function priority(): int;

    public function getConfigAdditional(array $config): array;
}
