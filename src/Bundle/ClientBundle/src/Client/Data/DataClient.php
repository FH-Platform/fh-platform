<?php

namespace FHPlatform\ClientBundle\Client\Data;

use Elastica\Document;
use Elastica\Response;
use FHPlatform\ClientBundle\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\Fetcher\DTO\Entity;

class DataClient
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    )
    {
    }

    public function upsertBatch(array $entities): array
    {
        if (0 === count($entities)) {
            return [];
        }

        //store connections by name
        $connections = [];
        foreach ($entities as $entity) {
            /** @var Entity $entity */
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $connections[$connection->getName()] = $connection;
        }

        //group entities by connection name and index name
        $entitiesGrouped = [];
        foreach ($entities as $entity) {
            /** @var Entity $entity */
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $entitiesGrouped[$connection->getName()][$index->getName()][] = [
                'identifier' => $entity->getIdentifier(),
                'data' =>  $entity->getData(),
            ];
        }

        //do the upsert for each index
        $responses = [];
        foreach ($entitiesGrouped as $connectionName => $indexes){
            $client = $this->connectionFetcher->fetch($connections[$connectionName]);

            foreach ($indexes as $indexName => $entities){
                $index = $client->getIndex($indexName);

                $documents = [];
                foreach ($entities as $entity){
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

        //return array of responses
        return $responses;
    }

    public function deleteBatch(array $entities): ?Response
    {
        return $response;
    }
}
