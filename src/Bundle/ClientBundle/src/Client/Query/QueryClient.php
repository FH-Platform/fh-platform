<?php

namespace FHPlatform\ClientBundle\Client\Query;

use Elastica\Query;
use Elastica\Search;
use FHPlatform\ClientBundle\Provider\ProviderInterface;
use FHPlatform\ConfigBundle\DTO\Index;

class QueryClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    public function searchPrepare(Index $index, mixed $query = null): Search
    {
        return $this->provider->searchPrepare($index, $query);
    }

    public function searchResults(Index $index, ?Query $query = null, $limit = null, $offset = 0): array
    {
        return $this->provider->searchResults($index, $query, $limit, $offset);
    }
}
