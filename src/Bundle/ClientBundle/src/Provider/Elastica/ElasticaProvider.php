<?php

namespace FHPlatform\ClientBundle\Provider\Elastica;

use Elastica\Document;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Index;

class ElasticaProvider
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Index $index, mixed $identifier, array $data) : mixed
    {
        $connection = $index->getConnection();

        $client = $this->connectionFetcher->fetch($connection);

        $index = $client->getIndex($connection->getPrefix().$index->getName());

        $document = new Document($identifier, $data);
        $document->setIndex($index);
        $document->setDocAsUpsert(true);

        return $document;
    }

    public function documentsUpsert(Index $index, mixed $documents) : mixed
    {
        $connection = $index->getConnection();

        $client = $this->connectionFetcher->fetch($connection);

        return $client->updateDocuments($documents);
    }

    public function documentsDelete(Index $index, mixed $documents) : mixed
    {
        $connection = $index->getConnection();

        $client = $this->connectionFetcher->fetch($connection);

        return $client->deleteDocuments($documents);
    }

    public function indexRefresh(Index $index) : mixed
    {
        $connection = $index->getConnection();
        $indexNameWithPrefix = $connection->getPrefix().$index->getName();

        $client = $this->connectionFetcher->fetch($connection);

        $index = $client->getIndex($indexNameWithPrefix);

        return $index->refresh();
    }
}
