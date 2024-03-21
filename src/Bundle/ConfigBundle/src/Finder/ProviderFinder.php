<?php

namespace FHPlatform\ConfigBundle\Finder;

use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;

class ProviderFinder
{
    public function __construct(
        private readonly ConfigProvider $taggedProvider,
    ) {
    }

    public function findProviderIndex(string $className, bool $throw = true): ?ProviderIndexInterface
    {
        // TODO find in connections
        foreach ($this->taggedProvider->getProvidersIndex() as $provider) {
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        if ($throw) {
            // TODO
            throw new ProviderForClassNameNotExists();
        }

        return null;
    }

    public function findProviderEntity(string $className, bool $throw = true): ?ProviderEntityInterface
    {
        foreach ($this->taggedProvider->getProvidersEntity() as $provider) {
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        if ($throw) {
            // TODO
            throw new ProviderForClassNameNotExists();
        }

        return null;
    }

    public function findProviderEntityRelated(string $className, bool $throw = true): ?ProviderEntityRelatedInterface
    {
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        if ($throw) {
            // TODO
            throw new ProviderForClassNameNotExists();
        }

        return null;
    }
}
