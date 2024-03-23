<?php

namespace FHPlatform\Component\Client\Provider\Data;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\Config\DTO\Entity;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

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

                $documentsUpsert = array_merge($documents[ChangedEntityDTO::TYPE_CREATE] ?? [], $documents[ChangedEntityDTO::TYPE_UPDATE] ?? []);
                $documentsDelete = $documents[ChangedEntityDTO::TYPE_DELETE] ?? [];

                if (count($documentsUpsert) > 0) {
                    $responses[] = $this->provider->documentsUpsert($index, $documentsUpsert);
                }

                if (count($documentsDelete) > 0) {
                    $responses[] = $this->provider->documentsDelete($index, $documentsDelete);
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
            $entitiesGrouped[$connectionName][$indexNameWithPrefix][$entity->getType()][] = $this->provider->documentPrepare($entity);
        }

        return $entitiesGrouped;
    }
}
