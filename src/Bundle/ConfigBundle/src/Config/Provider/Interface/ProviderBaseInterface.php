<?php

namespace FHPlatform\ConfigBundle\Config\Provider\Interface;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getAdditionalConfig(): array;
}
