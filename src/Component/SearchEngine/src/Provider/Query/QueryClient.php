<?php

namespace FHPlatform\Component\SearchEngine\Provider\Query;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Provider\SearchEngineAdapterInterface;

class QueryClient
{
    public function __construct(
        private readonly SearchEngineAdapterInterface $provider,
    ) {
    }

    public function getResultHits(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $results = $this->provider->searchResults($index, $query, $limit, $offset);

        return $results['hits']['hits'];
    }
}
