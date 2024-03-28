<?php

namespace FHPlatform\Component\Config\Config\Provider\Trait;

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
}
