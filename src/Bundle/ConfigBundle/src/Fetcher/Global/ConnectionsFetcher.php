<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;

class ConnectionsFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetch(): array
    {
        $connections = [];

        foreach ($this->taggedProvider->getProvidersConnection() as $connectionProvider) {
            /* @var ProviderConnection $connectionProvider */
            $connections[] = new Connection($connectionProvider->getName(), $connectionProvider->getIndexPrefix(), $connectionProvider->getElasticaConfig());
        }

        return $connections;
    }
}
