<?php

namespace FHPlatform\Component\Client\Provider\Query;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\Config\DTO\Index;

class QueryClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    public function getResultHits(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $results = $this->provider->searchResults($index, $query, $limit, $offset);

        return $results['hits']['hits'];
    }
}
