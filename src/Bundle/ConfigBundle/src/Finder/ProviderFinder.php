<?php

namespace FHPlatform\ConfigBundle\Finder;

use FHPlatform\ConfigBundle\Exception\ProviderForClassNameNotExists;
use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\ProviderIndex;

class ProviderFinder
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function findProviderIndex(string $className): ProviderIndex
    {
        foreach ($this->taggedProvider->getProvidersIndex() as $provider) {
            /** @var ProviderEntity $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }

    public function findProviderEntity(string $className): ?ProviderEntity
    {
        foreach ($this->taggedProvider->getProvidersEntity() as $provider) {
            /** @var ProviderEntity $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }

    public function findProviderEntityRelated(string $className): ?EntityRelatedInterface
    {
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
            /** @var ProviderEntityRelated $provider */
            if ($provider->getClassName() === $className) {
                return $provider;
            }
        }

        // TODO
        throw new ProviderForClassNameNotExists();
    }
}
