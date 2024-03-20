<?php

namespace FHPlatform\ClientBundle\Provider\Elastica;

use Elastica\Document;
use Elastica\Result;
use Elastica\Search;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ClientBundle\Provider\ProviderInterface;
use FHPlatform\ConfigBundle\DTO\Index;

class ElasticaProvider implements ProviderInterface
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Index $index, mixed $identifier, array $data): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $index = $client->getIndex($index->getConnection()->getPrefix().$index->getName());

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
        $client = $this->connectionFetcher->fetchByIndex($index);

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

        return $index->refresh();
    }

    public function searchPrepare(Index $index, mixed $query = null): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

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
