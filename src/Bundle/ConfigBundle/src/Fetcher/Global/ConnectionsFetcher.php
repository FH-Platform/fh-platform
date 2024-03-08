<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;
use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;

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
            /* @var ConnectionProvider $connectionProvider */
            $connections[] = new Connection($connectionProvider->getName(), $connectionProvider->getIndexPrefix(), $connectionProvider->getElasticaConfig());
        }

        return $connections;
    }
}
