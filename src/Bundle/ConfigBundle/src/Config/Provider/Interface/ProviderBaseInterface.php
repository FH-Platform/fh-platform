<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface;

use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getAdditionalConfig(): array;
}
