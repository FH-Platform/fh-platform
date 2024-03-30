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

    public function getResults(Index $index, mixed $query = null, $type = self::TYPE_RAW): array
    {
        $results = $this->adapter->search($index, $query);

        if (self::TYPE_IDENTIFIERS === $type) {
            $results = $this->adapter->convertSearchResults($results);

            $identifiers = [];
            foreach ($results as $result) {
                $identifiers[] = $result['id'];
            }
            $identifiers = array_unique($identifiers);

            return $identifiers;
        } elseif (self::TYPE_ENTITIES === $type) {
            $results = $this->adapter->convertSearchResults($results);

            $identifiers = [];
            foreach ($results as $result) {
                $identifiers[] = $result['id'];
            }
            $identifiers = array_unique($identifiers);

            // TODO sort by ids (mysql vs sqlite)
            return $this->persistence->getEntities($index->getClassName(), $identifiers);
        } elseif (self::TYPE_ENTITIES_RAW === $type) {
            $results = $this->adapter->convertSearchResults($results);

            $identifiers = $resultsResponse = [];
            foreach ($results as $result) {
                $id = $result['id'];

                $resultsResponse[$id] = ['raw' => $result];
                $identifiers[] = $id;
            }
            $identifiers = array_unique($identifiers);

            $entities = $this->persistence->getEntities($index->getClassName(), $identifiers);

            foreach ($entities as $entity) {
                $identifierValue = $this->persistence->getIdentifierValue($entity);

                $resultsResponse[$identifierValue] = ['entity' => $entity];
            }

            return $resultsResponse;
        } elseif (self::TYPE_RAW_SOURCE === $type) {
            return $this->adapter->convertSearchResults($results);
        }

        return $results;
    }
}
