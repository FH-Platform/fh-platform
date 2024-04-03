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
    public const TYPE_SOURCES = 'sources';
    public const TYPE_RAW_WITH_ENTITIES = 'entities_raw';

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
        } elseif (self::TYPE_RAW_WITH_ENTITIES === $type) {
            $identifiers = $this->searchEngine->convertResultsToIdentifiers($results);

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            $entitiesByIds = [];
            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);
                $entitiesByIds[$identifierValue] = $entity;
            }

            foreach ($results['hits']['hits'] as $key => $result) {
                $results['hits']['hits'][$key]['_entity'] = $entitiesByIds[$result['_id']] ?? null;
            }

            return $results;
        }

        throw new \Exception('Unsupported type');
    }
}
