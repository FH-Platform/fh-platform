<?php

namespace FHPlatform\Component\SearchEngineEsElastica;

use Elastica\Request;
use Elastica\Search;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Provider\SearchEngineAdapterInterface;
use FHPlatform\Component\SearchEngineEsElastica\Connection\ConnectionFetcher;

class SearchEngineElasticaAdapter implements SearchEngineAdapterInterface
{
    private ConnectionFetcher $connectionFetcher;

    public function __construct()
    {
        $this->connectionFetcher = new ConnectionFetcher();
    }

    public function documentsUpdate(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $documentsElasticaUpsert = [];
        $documentsElasticaDelete = [];
        $indexElastica = $this->getIndex($index);

        foreach ($documents as $document) {
            $documentElastica = new \Elastica\Document($document->getIdentifier(), $document->getData(), $indexElastica);

            /** @var Document $document */
            if (0 === count($document->getData())) {
                $documentsElasticaDelete[] = $documentElastica;
            } else {
                $documentElastica->setDocAsUpsert(true);

                $documentsElasticaUpsert[] = $documentElastica;
            }
        }

        if (count($documentsElasticaUpsert)) {
            $client->updateDocuments($documentsElasticaUpsert);
        }

        if (count($documentsElasticaDelete) > 0) {
            $client->deleteDocuments($documentsElasticaDelete);
        }

        return null;
    }

    public function indexRefresh(Index $index): mixed
    {
        $index = $this->getIndex($index);

        return $index->refresh();
    }

    public function indexDelete(Index $index): void
    {
        $index = $this->getIndex($index);

        if ($index->exists()) {
            $index->delete();
        }
    }

    public function indexCreate(Index $index): \Elastica\Index
    {
        $index = $this->getIndex($index);

        if (!$index->exists()) {
            $index->create();

            // TODO
            /*$mappingObject = new Mapping();
            $mappingObject->setProperties($mapping);
            $mappingObject->send($index);*/
        }

        return $index;
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $client->request(sprintf('%s*', $connection->getPrefix()), Request::DELETE)->getStatus();
    }

    public function indexesGetAllInConnection(Connection $connection): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $indices = $client->getCluster()->getIndexNames();
        $indicesFiltered = [];

        foreach ($indices as $index) {
            if (str_starts_with($index, $connection->getPrefix())) {
                $indicesFiltered[] = $index;
            }
        }

        sort($indicesFiltered);

        return $indicesFiltered;
    }

    public function searchResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $index = $this->getIndex($index);

        $search = new Search($client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        return $search->search()->getResponse()->getData();
    }

    private function getIndex(Index $index): \Elastica\Index
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->getIndex($index->getNameWithPrefix());
    }
}
