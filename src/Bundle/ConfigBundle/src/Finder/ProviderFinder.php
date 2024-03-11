<?php

namespace FHPlatform\ConfigBundle\Finder;

use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;
use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntity;

class ProviderFinder
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function findProviderIndex(string $className): ProviderIndexInterface
    {
        foreach ($this->taggedProvider->getProvidersIndex() as $provider) {
            /** @var ProviderIndexInterface $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }

    public function findProviderEntity(string $className): ProviderEntityInterface
    {
        foreach ($this->taggedProvider->getProvidersEntity() as $provider) {
            /** @var ProviderEntityInterface $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }

    public function findProviderEntityRelated(string $className): ProviderEntityRelatedInterface
    {
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
            /** @var ProviderEntityRelatedInterface $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }
}
