<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter;

class QueryManager
{
    public const TYPE_RAW = 'raw';
    public const TYPE_RAW_SOURCE = 'raw_source';
    public const TYPE_ENTITIES = 'entities';
    public const TYPE_ENTITIES_RAW = 'entities_raw';

    public function __construct(
        private readonly SearchEngineAdapter $adapter,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function getResults(Index $index, mixed $query = null, $limit = 100, $offset = 0, $type = self::TYPE_RAW): array
    {
        $results = $this->adapter->queryResults($index, $query, $limit, $offset);

        if (self::TYPE_ENTITIES === $type) {
            // TODO for ES
            $identifiers = [];
            foreach ($results['hits']['hits'] as $result) {
                $identifiers[] = $result['_id'];
            }

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            return $entities;
        } elseif (self::TYPE_ENTITIES_RAW === $type) {
            // TODO for ES
            $identifiers = [];
            $resultsResponse = [];
            foreach ($results['hits']['hits'] as $result) {
                $id = $result['_id'];
                $identifiers[] = $id;
                $resultsResponse[$id] = ['raw' => $result];
            }

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);

                $resultsResponse[$identifierValue] = ['entity' => $entity];
            }

            return $resultsResponse;
        } elseif (self::TYPE_RAW_SOURCE === $type) {
            $resultsResponse = [];
            foreach ($results['hits']['hits'] as $result) {
                $resultsResponse[] = $result['_source'];
            }

            return $resultsResponse;
        }

        return $results;
    }
}
