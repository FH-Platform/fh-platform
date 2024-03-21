<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

use FHPlatform\Component\Config\Util\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getAdditionalConfig(): array;
}
