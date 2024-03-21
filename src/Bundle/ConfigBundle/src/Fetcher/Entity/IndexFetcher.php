<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class IndexFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetchClassNamesIndex(): array
    {
        $classNames = [];
        foreach ($this->taggedProvider->getProvidersEntity() as $provider) {
            $classNames[$provider->getClassName()] = $provider->getClassName();
        }

        return $classNames;
    }
}
