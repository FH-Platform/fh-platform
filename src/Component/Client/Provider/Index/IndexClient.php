<?php

namespace FHPlatform\Component\Client\Provider\Index;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Bundle\ConfigBundle\DTO\Connection;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

class IndexClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    public function deleteIndex(Index $index): void
    {
        $this->provider->indexDelete($index);
    }

    public function createIndex(Index $index): mixed
    {
        return $this->provider->indexCreate($index);
    }

    public function recreateIndex(Index $index): mixed
    {
        $this->provider->indexDelete($index);

        return $this->provider->indexCreate($index);
    }

    public function getAllIndexesInConnection(Connection $connection): array
    {
        return $this->provider->indexesGetAllInConnection($connection);
    }

    public function deleteAllIndexesInConnection(Connection $connection): void
    {
        $this->provider->indexesDeleteAllInConnection($connection);
    }
}
