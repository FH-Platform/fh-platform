<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;

class QueryManager
{
    public const TYPE_RAW = 'raw';
    public const TYPE_IDENTIFIERS = 'ids';
    public const TYPE_ENTITIES = 'entities';
    public const TYPE_SOURCES = 'raw_source';
    public const TYPE_SOURCES_WITH_ENTITIES = 'entities_raw';

    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function getResults(Index $index, mixed $query = null, $type = self::TYPE_RAW): array
    {
        $results = $this->searchEngine->search($index, $query);

        if (self::TYPE_RAW === $type) {
            return $results;
        } elseif (self::TYPE_IDENTIFIERS === $type) {
            return $this->searchEngine->convertResultsToIdentifiers($results);
        } elseif (self::TYPE_ENTITIES === $type) {
            $identifiers = $this->searchEngine->convertResultsToIdentifiers($results);

            return $this->persistence->getEntities($index->getClassName(), $identifiers);
        } elseif (self::TYPE_SOURCES === $type) {
            return $this->searchEngine->convertResultsToSources($results);
        } elseif (self::TYPE_SOURCES_WITH_ENTITIES === $type) {
            $sources = $this->searchEngine->convertResultsToSources($results);

            $identifiers = $resultsResponse = [];
            foreach ($sources as $source) {
                $id = $source['id'];

                $resultsResponse[$id] = ['source' => $source];
                $identifiers[] = $id;
            }
            $identifiers = array_unique($identifiers);

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);

                $resultsResponse[$identifierValue] = ['entity' => $entity];
            }

            return $resultsResponse;
        }

        throw new \Exception('Unsupported type');
    }
}
