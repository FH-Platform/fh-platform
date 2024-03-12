<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getAdditionalConfig(): array;
}
