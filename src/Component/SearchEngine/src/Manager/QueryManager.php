<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;

class QueryManager
{
    public const TYPE_RAW = 'raw';
    public const TYPE_IDENTIFIERS = 'identifiers';
    public const TYPE_SOURCES = 'sources';

    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
    ) {
    }

    public function getResults(Index $index, array $query = [], $type = self::TYPE_RAW): array
    {
        $results = $this->searchEngine->search($index, $query);

        if (self::TYPE_RAW === $type) {
            return $results;
        } elseif (self::TYPE_IDENTIFIERS === $type) {
            return $this->searchEngine->convertResultsToIdentifiers($results);
        } elseif (self::TYPE_SOURCES === $type) {
            return $this->searchEngine->convertResultsToSources($results);
        }

        throw new \Exception('Unsupported type');
    }
}
