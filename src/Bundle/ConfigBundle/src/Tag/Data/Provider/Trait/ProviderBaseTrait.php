<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider\Trait;

trait ProviderBaseTrait
{
    public function getClassName(): string
    {
        // TODO
        throw new \Exception('not implemented.');
    }

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
