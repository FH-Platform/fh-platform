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

    public function getSearch(Index $index, mixed $query = null): mixed
    {
        return $this->provider->searchPrepare($index, $query);
    }

    public function getResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        return $this->provider->searchResults($index, $query, $limit, $offset);
    }
}
