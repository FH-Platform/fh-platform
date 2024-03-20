<?php

namespace FHPlatform\ClientBundle\Provider\Elastica;

use Elastica\Document;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Index;
use function Ramsey\Uuid\v1;

class ElasticaProvider
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Index $index, mixed $identifier, array $data) : mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $index = $client->getIndex($index->getConnection()->getPrefix().$index->getName());

        $document = new Document($identifier, $data);
        $document->setIndex($index);
        $document->setDocAsUpsert(true);

        return $document;
    }

    public function documentsUpsert(Index $index, mixed $documents) : mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->updateDocuments($documents);
    }

    public function documentsDelete(Index $index, mixed $documents) : mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->deleteDocuments($documents);
    }

    public function indexRefresh(Index $index) : mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

        return $index->refresh();
    }
}
