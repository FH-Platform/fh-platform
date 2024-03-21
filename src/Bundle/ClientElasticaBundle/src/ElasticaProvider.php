<?php

namespace FHPlatform\Bundle\ClientElasticaBundle;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use Elastica\Document;
use Elastica\Request;
use Elastica\Result;
use Elastica\Search;
use FHPlatform\Bundle\ClientElasticaBundle\Connection\ConnectionFetcher;
use FHPlatform\Component\Client\Provider\ProviderInterface;

class ElasticaProvider implements ProviderInterface
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Index $index, mixed $identifier, array $data, bool $upsert): Document
    {
        $index = $this->getIndex($index);

        $document = new Document($identifier, $data);
        $document->setIndex($index);
        $document->setDocAsUpsert(true);

        return $document;
    }

    public function documentsUpsert(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->updateDocuments($documents);
    }

    public function documentsDelete(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->deleteDocuments($documents);
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

    public function searchPrepare(Index $index, mixed $query = null): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $index = $this->getIndex($index);

        $search = new Search($client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        return $search;
    }

    public function searchResults(Index $index, mixed $query = null, $limit = null, $offset = 0): mixed
    {
        $search = $this->searchPrepare($index, $query);

        return $this->scrollSearch($search, $limit, $offset);
    }

    private function getIndex(Index $index): \Elastica\Index
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->getIndex($index->getNameWithPrefix());
    }

    private function scrollSearch(Search $search, $limit, $offset): array
    {
        $results = [];
        $processedResults = 0;

        foreach ($search->scroll() as $scrollId => $resultSet) {
            if (null !== $resultSet && count($resultSet)) {
                foreach ($resultSet as $result) {
                    /* @var Result $result */

                    if ($processedResults < $offset) {
                        ++$processedResults;
                        continue;
                    }

                    $results[$result->getId()] = $result;

                    if (count($results) === $limit) {
                        break 2;
                    }
                }
            }
        }

        return $results;
    }
}
