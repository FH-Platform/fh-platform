<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;

interface ProviderBaseInterface extends PriorityInterface
{
    public function getClassName(): string;

    public function getConnection(): string;

    public function getAdditionalConfig(): array;
}
