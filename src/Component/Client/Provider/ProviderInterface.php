<?php

namespace FHPlatform\Component\Client\Provider;

use FHPlatform\Bundle\ConfigBundle\DTO\Connection;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

interface ProviderInterface
{
    public function documentPrepare(Index $index, mixed $identifier, array $data, bool $upsert);

    public function documentsUpsert(Index $index, mixed $documents);

    public function documentsDelete(Index $index, mixed $documents);

    public function indexRefresh(Index $index): mixed;

    public function indexDelete(Index $index): void;

    public function indexCreate(Index $index): mixed;

    public function indexesDeleteAllInConnection(Connection $connection): void;

    public function indexesGetAllInConnection(Connection $connection): array;

    public function searchPrepare(Index $index, mixed $query = null): mixed;

    public function searchResults(Index $index, mixed $query = null, $limit = null, $offset = 0): mixed;
}
