<?php

namespace FHPlatform\Bundle\ClientBundle\Client\Data;

use FHPlatform\Bundle\ClientBundle\Provider\ProviderInterface;
use FHPlatform\Bundle\ConfigBundle\DTO\Entity;

class DataClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    /** @param Entity[] $entities */
    public function syncEntities(array $entities): array
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

                if (count($documents['upsert'] ?? []) > 0) {
                    $responses[] = $this->provider->documentsUpsert($index, $documents['upsert'] ?? []);
                }

                if (count($documents['delete'] ?? []) > 0) {
                    $responses[] = $this->provider->documentsDelete($index, $documents['delete'] ?? []);
                }

                // refresh index
                $this->provider->indexRefresh($index);
            }
        }

        // return array of responses
        return $responses;
    }

    /** @param Entity[] $entities */
    private function groupEntities(array $entities): array
    {
        $entitiesGrouped = [];

        foreach ($entities as $entity) {
            $index = $entity->getIndex();

            $connectionName = $index->getConnection()->getName();
            $indexNameWithPrefix = $index->getNameWithPrefix();

            $entitiesGrouped[$connectionName][$indexNameWithPrefix]['index'] = $index;

            if ($entity->getUpsert()) {
                $entitiesGrouped[$connectionName][$indexNameWithPrefix]['upsert'][] = $this->provider->documentPrepare($index, $entity->getIdentifier(), $entity->getData(), true);
            } else {
                $entitiesGrouped[$connectionName][$indexNameWithPrefix]['delete'][] = $this->provider->documentPrepare($index, $entity->getIdentifier(), [], false);
            }
        }

        return $entitiesGrouped;
    }
}
