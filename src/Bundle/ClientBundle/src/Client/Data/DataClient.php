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

        // store connections by name
        $connections = $this->groupConnections($entities);

        // group entities by connection name and index name
        $entitiesGrouped = $this->groupEntities($entities);

        // do the delete for each index
        $responses = [];
        foreach ($entitiesGrouped as $connectionName => $indexes) {
            $connection = $connections[$connectionName];

            foreach ($indexes as $indexName => $entities) {
                $documents = [];
                foreach ($entities as $entity) {

                    //prepare documents
                    $identifier = $entity['identifier'];
                    $data = $entity['data'];
                    $documents[] = $this->elasticaProvider->documentPrepare($connection, $indexName, $identifier, $data);
                }

                //do the deletes for each index on connection
                if($upsert){
                    $responses[] = $this->elasticaProvider->documentsUpsert($connection, $documents);
                }else{
                    $responses[] = $this->elasticaProvider->documentsDelete($connection, $documents);
                }

                //refresh index
                $this->elasticaProvider->indexRefresh($connection, $indexName);
            }
        }

        // return array of responses
        return $responses;
    }

    /** @param Entity[] $entities */
    private function groupConnections(array $entities): array
    {
        $connections = [];
        foreach ($entities as $entity) {
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $connections[$connection->getName()] = $connection;
        }

        return $connections;
    }

    /** @param Entity[] $entities */
    private function groupEntities(array $entities): array
    {
        $entitiesGrouped = [];
        foreach ($entities as $entity) {
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $connectionName = $connection->getName();
            $indexNameWithPrefix = $connection->getPrefix().$index->getName();

            $entitiesGrouped[$connectionName][$indexNameWithPrefix][] = [
                'identifier' => $entity->getIdentifier(),
                'data' => $entity->getData(),
            ];
        }

        return $entitiesGrouped;
    }
}
