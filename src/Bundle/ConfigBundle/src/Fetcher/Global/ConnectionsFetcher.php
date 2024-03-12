<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class ConnectionsFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetch(): array
    {
        $connections = [];

        foreach ($this->taggedProvider->getProvidersConnection() as $providerConnection) {
            $connections[] = $this->convertProviderToDto($providerConnection);
        }

        return $connections;
    }

    private function convertProviderToDto(ProviderConnection $providerConnection): Connection
    {
        return new Connection($providerConnection->getName(), $providerConnection->getIndexPrefix(), $providerConnection->getElasticaConfig());
    }
}
