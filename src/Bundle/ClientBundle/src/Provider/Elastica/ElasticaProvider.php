<?php

namespace FHPlatform\ClientBundle\Provider\Elastica;

use Elastica\Document;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\DTO\Connection;

class ElasticaProvider
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Connection $connection, string $indexName, mixed $identifier, array $data) : mixed
    {
        $client = $this->connectionFetcher->fetch($connection);

        $index = $client->getIndex($indexName);

        $document = new Document($identifier, $data);
        $document->setIndex($index);
        $document->setDocAsUpsert(true);

        return $document;
    }

    public function documentsUpsert(Connection $connection, mixed $documents) : mixed
    {
        $client = $this->connectionFetcher->fetch($connection);

        return $client->updateDocuments($documents);
    }

    public function documentsDelete(Connection $connection, mixed $documents) : mixed
    {
        $client = $this->connectionFetcher->fetch($connection);

        return $client->deleteDocuments($documents);
    }

    public function indexRefresh(Connection $connection, string $indexName) : mixed
    {
        $client = $this->connectionFetcher->fetch($connection);

        $index = $client->getIndex($indexName);

        return $index->refresh();
    }
}
