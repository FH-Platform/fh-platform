<?php

namespace FHPlatform\ClientBundle\Client\Data;

use Elastica\Document;
use FHPlatform\ClientBundle\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\DTO\Entity;

class DataClient
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function upsertBatch(array $entities): array
    {
        if (0 === count($entities)) {
            return [];
        }

        // store connections by name
        $connections = $this->groupConnections($entities);

        // group entities by connection name and index name
        $entitiesGrouped = $this->groupEntities($entities);

        // do the upsert for each index
        $responses = [];
        foreach ($entitiesGrouped as $connectionName => $indexes) {
            $client = $this->connectionFetcher->fetch($connections[$connectionName]);

            foreach ($indexes as $indexName => $entities) {
                $index = $client->getIndex($indexName);

                $documents = [];
                foreach ($entities as $entity) {
                    $identifier = $entity['identifier'];
                    $data = $entity['data'];

                    $document = new Document($identifier, $data);
                    $document->setIndex($index);
                    $document->setDocAsUpsert(true);
                    $documents[] = $document;
                }

                $response = $client->updateDocuments($documents);
                $responses = [$response];

                $index->refresh();
            }
        }

        // return array of responses
        return $responses;
    }

    public function deleteBatch(array $entities): array
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
            $client = $this->connectionFetcher->fetch($connections[$connectionName]);

            foreach ($indexes as $indexName => $entities) {
                $index = $client->getIndex($indexName);

                $documents = [];
                foreach ($entities as $entity) {
                    $identifier = $entity['identifier'];

                    $document = new Document($identifier);
                    $document->setIndex($index);
                    $documents[] = $document;
                }

                $response = $client->deleteDocuments($documents);
                $responses = [$response];

                $index->refresh();
            }
        }

        // return array of responses
        return $responses;
    }

    private function groupConnections(array $entities): array
    {
        $connections = [];
        foreach ($entities as $entity) {
            /** @var Entity $entity */
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $connections[$connection->getName()] = $connection;
        }

        return $connections;
    }

    private function groupEntities(array $entities): array
    {
        $entitiesGrouped = [];
        foreach ($entities as $entity) {
            /** @var Entity $entity */
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
