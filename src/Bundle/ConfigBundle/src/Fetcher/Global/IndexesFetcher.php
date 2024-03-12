<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderIndex;

class IndexesFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly IndexFetcher $indexFetcher,
    ) {
    }

    public function fetch(): array
    {
        $indexes = [];
        foreach ($this->taggedProvider->getProvidersIndex() as $indexProvider) {
            /* @var ProviderIndex $indexProvider */
            $indexes[] = $this->indexFetcher->fetch($indexProvider->getClassName());
        }

        return $indexes;
    }
}
