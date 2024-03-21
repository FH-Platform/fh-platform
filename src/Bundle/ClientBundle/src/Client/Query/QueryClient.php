<?php

namespace FHPlatform\Bundle\ClientBundle\Client\Query;

use FHPlatform\Bundle\ClientBundle\Provider\ProviderInterface;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;

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
