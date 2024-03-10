<?php

namespace FHPlatform\ClientBundle\Client\Data;

use Elastica\Document;
use Elastica\Response;
use FHPlatform\ClientBundle\Client\ElasticaClient;
use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Client\Query\QueryClient;

class DataClient
{
    public function __construct(
        private readonly ElasticaClient $client,
        private readonly IndexClient $indexClient,
        private readonly QueryClient $queryClient, // TODO -> TMP, public
    ) {
    }

    public function upsertBatch(array $entities): ?Response
    {
        if (0 === count($entities)) {
            return null;
        }

        $indexes = [];
        $documents = [];
        foreach ($entities as $entity) {
            $className = $entity['className'];
            $identifier = $entity['identifier'];
            $data = $entity['data'];

            $index = $this->indexClient->getIndex($className);

            $indexes[$index->getName()] = $index;

            $document = new Document($identifier, $data);
            $document->setIndex($index);
            $document->setDocAsUpsert(true);
            $documents[] = $document;
        }

        $response = $this->client->updateDocuments($documents);

        foreach ($indexes as $index) {
            $index->refresh();
        }

        return $response;
    }

    public function deleteBatch(array $entities): ?Response
    {
        if (0 === count($entities)) {
            return null;
        }

        $indexes = [];
        $documents = [];
        foreach ($entities as $entity) {
            $className = $entity['className'];
            $identifier = $entity['identifier'];

            $index = $this->indexClient->getIndex($className);

            $indexes[$index->getName()] = $index;

            $document = new Document($identifier, []);
            $document->setIndex($index);
            $documents[] = $document;
        }

        $response = $this->client->deleteDocuments($documents);

        foreach ($indexes as $index) {
            $index->refresh();
        }

        return $response;
    }
}
