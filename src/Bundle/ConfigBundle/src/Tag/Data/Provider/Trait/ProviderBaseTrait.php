<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider\Trait;

trait ProviderBaseTrait
{
    public function getClassName(): string
    {
        // TODO
        throw new \Exception('not implemented.');
    }

    public function getAdditionalConfig(): array
    {
        return [];
    }
}
