<?php

namespace FHPlatform\ClientBundle\Client\Data;

use FHPlatform\ClientBundle\Provider\Elastica\ElasticaProvider;
use FHPlatform\ConfigBundle\DTO\Entity;

class DataClient
{
    public function __construct(
        private readonly ElasticaProvider $elasticaProvider,
    ) {
    }

    /** @param Entity[] $entities */
    public function upsertBatch(array $entities): array
    {
        return $this->syncEntities($entities, true);
    }

    /** @param Entity[] $entities */
    public function deleteBatch(array $entities): array
    {
        return $this->syncEntities($entities, false);
    }

    private function syncEntities(array $entities, bool $upsert) : array
    {
        if (0 === count($entities)) {
            return [];
        }

        // group indexes and entities by connection and index
        list($indexesGrouped,  $entitiesGrouped) = $this->groupEntities($entities);

        $responses = [];
        foreach ($entitiesGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $entities) {
                $index = $indexesGrouped[$connectionName][$indexName];

                $documents = [];
                foreach ($entities as $entity) {

                    //prepare documents
                    $identifier = $entity['identifier'];
                    $data = $entity['data'];
                    $documents[] = $this->elasticaProvider->documentPrepare($index, $identifier, $data);
                }

                //do the deletes for each index on connection
                if($upsert){
                    $responses[] = $this->elasticaProvider->documentsUpsert($index, $documents);
                }else{
                    $responses[] = $this->elasticaProvider->documentsDelete($index, $documents);
                }

                //refresh index
                $this->elasticaProvider->indexRefresh($index);
            }
        }

        // return array of responses
        return $responses;
    }

    private function groupEntities(array $entities): array
    {
        $indexesGrouped =  $entitiesGrouped = [];

        foreach ($entities as $entity) {
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $connectionName = $connection->getName();
            $indexNameWithPrefix = $connection->getPrefix().$index->getName();

            $indexesGrouped[$connectionName][$indexNameWithPrefix] = $index;
            $entitiesGrouped[$connectionName][$indexNameWithPrefix][] = [
                'identifier' => $entity->getIdentifier(),
                'data' => $entity->getData(),
            ];
        }

        return [$indexesGrouped,  $entitiesGrouped];
    }
}
