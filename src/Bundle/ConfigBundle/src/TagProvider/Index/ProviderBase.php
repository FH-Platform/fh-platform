<?php

namespace FHPlatform\ConfigBundle\TagProvider\Index;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

abstract class ProviderBase implements PriorityInterface
{
    abstract public function getClassName(): string;

    public function getConnection(): string
    {
        return 'default';
    }

    public function priority(): int
    {
        return 0;
    }

    public function getAdditionalConfig(): array
    {
        return [];
    }
}
