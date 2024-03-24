<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;

class QueryManager
{
    public const TYPE_RAW = 'raw';
    public const TYPE_ENTITIES = 'entities';

    public function __construct(
        private readonly ManagerAdapterInterface $provider,
    ) {
    }

    public function getResultHits(Index $index, mixed $query = null, $limit = 100, $offset = 0, $type = self::TYPE_RAW): array
    {
        $results = $this->provider->searchResults($index, $query, $limit, $offset);

        if (self::TYPE_RAW === $type) {
            return $results;
        }

        // TODO

        return [];
    }
}
