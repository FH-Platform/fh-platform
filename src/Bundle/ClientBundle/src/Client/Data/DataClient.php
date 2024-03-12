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
    ) {
    }

    public function upsertBatch(array $entities): ?Response
    {
        if (0 === count($entities)) {
            return null;
        }

        foreach ($entities as $entity) {
            /** @var Entity $entity */
            $index = $entity->getIndex();
            $connection = $index->getConnection();

            $client = $this->connectionFetcher->fetch($connection);

            $className = $entity->getClassName();
            $identifier = $entity->getIdentifier();
            $data = $entity->getData();
            $indexName = $entity->getIndex()->getName();

            $index = $client->getIndex($indexName);

            $indexes[$index->getName()] = $index;

            $document = new Document($identifier, $data);
            $document->setIndex($index);
            $document->setDocAsUpsert(true);
            $documents[] = $document;
        }

        $response = $client->updateDocuments($documents);

        foreach ($indexes as $index) {
            $index->refresh();
        }

        return $response;
    }

    public function deleteBatch(array $entities): ?Response
    {
        return $response;
    }
}
