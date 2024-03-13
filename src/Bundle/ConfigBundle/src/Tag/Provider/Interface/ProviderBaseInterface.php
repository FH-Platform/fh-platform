<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getAdditionalConfig(): array;
}
