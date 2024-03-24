<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;

interface ManagerAdapterInterface
{
    /** @param Document[] $documents */
    public function documentsUpdate(Index $index, mixed $documents): mixed;

    public function indexRefresh(Index $index): mixed;

    public function indexDelete(Index $index): void;

    public function indexCreate(Index $index): mixed;

    public function indexesDeleteAllInConnection(Connection $connection): void;

    public function indexesGetAllInConnection(Connection $connection): array;

    public function searchResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): mixed;
}
