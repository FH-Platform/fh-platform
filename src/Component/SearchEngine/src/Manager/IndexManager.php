<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter;

class IndexManager
{
    public function __construct(
        private readonly SearchEngineAdapter $adapter,
    ) {
    }

    public function deleteIndex(Index $index): void
    {
        $this->adapter->indexDelete($index);
    }

    public function createIndex(Index $index): void
    {
        $this->adapter->indexCreate($index);
    }

    public function recreateIndex(Index $index): void
    {
        $this->adapter->indexDelete($index);

        $this->adapter->indexCreate($index);
    }

    public function getAllIndexesInConnection(Connection $connection): array
    {
        return $this->adapter->indexesGetAllInConnection($connection);
    }

    public function deleteAllIndexesInConnection(Connection $connection): void
    {
        $this->adapter->indexesDeleteAllInConnection($connection);
    }
}
