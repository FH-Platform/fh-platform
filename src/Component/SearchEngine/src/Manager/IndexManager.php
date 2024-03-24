<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;

class IndexManager
{
    public function __construct(
        private readonly ManagerAdapterInterface $provider,
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
