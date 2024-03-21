<?php

namespace FHPlatform\Component\Client\Provider\Query;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Client\Provider\ProviderInterface;

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

    public function getResults(Index $index, mixed $query = null, $limit = null, $offset = 0): array
    {
        return $this->provider->searchResults($index, $query, $limit, $offset);
    }
}
