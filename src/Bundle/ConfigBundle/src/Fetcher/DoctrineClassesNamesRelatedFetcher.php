<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class DoctrineClassesNamesRelatedFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetch(): array
    {
        $classNames = [];
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
            $classNames[$provider->getClassName()] = $provider->getClassName();
        }

        return $classNames;
    }
}
