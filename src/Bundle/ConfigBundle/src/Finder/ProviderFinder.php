<?php

namespace FHPlatform\ConfigBundle\Finder;

use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class ProviderFinder
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function findProviderIndex(string $className, bool $throw = true): ?ProviderIndexInterface
    {
        // TODO find in connections
        foreach ($this->taggedProvider->getProvidersIndex() as $provider) {
            /** @var ProviderIndexInterface $provider */
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
            /** @var ProviderEntityInterface $provider */
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
            /** @var ProviderEntityRelatedInterface $provider */
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
