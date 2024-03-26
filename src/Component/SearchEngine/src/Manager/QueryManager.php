<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface;

class QueryManager
{
    public const TYPE_IDENTIFIERS = 'ids';
    public const TYPE_RAW = 'raw';
    public const TYPE_RAW_SOURCE = 'raw_source';
    public const TYPE_ENTITIES = 'entities';
    public const TYPE_ENTITIES_RAW = 'entities_raw';

    public function __construct(
        private readonly SearchEngineInterface $adapter,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function getResults(Index $index, mixed $query = null, $limit = 100, $offset = 0, $type = self::TYPE_RAW): array
    {
        $results = $this->adapter->queryResults($index, $query, $limit, $offset);

        if (self::TYPE_IDENTIFIERS === $type) {
            $results = $this->adapter->convertResultsSource($results);

            $identifiers = [];
            foreach ($results as $result) {
                $identifiers[] = $result['id'];
            }

            return $identifiers;
        } elseif (self::TYPE_ENTITIES === $type) {
            $results = $this->adapter->convertResultsSource($results);

            $identifiers = [];
            foreach ($results as $result) {
                $identifiers[] = $result['id'];
            }

            // TODO sort by ids (mysql vs sqlite)
            return $this->persistence->getEntities($index->getClassName(), $identifiers);
        } elseif (self::TYPE_ENTITIES_RAW === $type) {
            $results = $this->adapter->convertResultsSource($results);

            $identifiers = $resultsResponse = [];
            foreach ($results as $result) {
                $id = $result['id'];

                $resultsResponse[$id] = ['raw' => $result];
                $identifiers[] = $id;
            }

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);

                $resultsResponse[$identifierValue] = ['entity' => $entity];
            }

            return $resultsResponse;
        } elseif (self::TYPE_RAW_SOURCE === $type) {
            return $this->adapter->convertResultsSource($results);
        }

        return $results;
    }
}
