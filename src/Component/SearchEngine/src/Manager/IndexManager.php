<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;

class IndexManager
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
    ) {
    }

    public function deleteIndex(Index $index): void
    {
        $this->searchEngine->indexDelete($index);
    }

    public function createIndex(Index $index): void
    {
        $this->searchEngine->indexCreate($index);
    }

    public function recreateIndex(Index $index): void
    {
        $this->searchEngine->indexDelete($index);

        $this->searchEngine->indexCreate($index);
    }

    public function getAllIndexesInConnection(Connection $connection): array
    {
        return $this->searchEngine->indexesGetAllInConnection($connection);
    }

    public function deleteAllIndexesInConnection(Connection $connection): void
    {
        $this->searchEngine->indexesDeleteAllInConnection($connection);
    }
}
