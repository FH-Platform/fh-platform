<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;

class QueryManager
{
    public function __construct(
        private readonly ManagerAdapterInterface $provider,
    ) {
    }

    public function getResultHits(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $results = $this->provider->searchResults($index, $query, $limit, $offset);

        return $results['hits']['hits'];
    }
}
