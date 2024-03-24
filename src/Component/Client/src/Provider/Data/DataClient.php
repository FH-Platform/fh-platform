<?php

namespace FHPlatform\Component\Client\Provider\Data;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\Config\DTO\Document;

class DataClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    /** @param Document[] $entities */
    public function syncDocuments(array $entities): array
    {
        if (0 === count($entities)) {
            return [];
        }

        // group indexes and entities by connection and index
        $entitiesGrouped = $this->groupEntities($entities);

        $responses = [];
        foreach ($entitiesGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $documents) {
                $index = $documents['index'];

                // do the upsert/delete for each index on connection

                if (count($documents['documents']) > 0) {
                    $responses[] = $this->provider->documentsUpdate($index, $documents['documents']);
                }

                // refresh index
                $this->provider->indexRefresh($index);
            }
        }

        // return array of responses
        return $responses;
    }

    /** @param Document[] $entities */
    private function groupEntities(array $entities): array
    {
        $entitiesGrouped = [];

        foreach ($entities as $entity) {
            $index = $entity->getIndex();

            $connectionName = $index->getConnection()->getName();
            $indexNameWithPrefix = $index->getNameWithPrefix();

            $entitiesGrouped[$connectionName][$indexNameWithPrefix]['index'] = $index;
            $entitiesGrouped[$connectionName][$indexNameWithPrefix]['documents'][] = $this->provider->documentPrepare($entity);
        }

        return $entitiesGrouped;
    }
}
