<?php

namespace FHPlatform\ClientBundle\Client\Query;

use Elastica\Query;
use Elastica\Result;
use Elastica\Search;
use FHPlatform\ClientBundle\Client\ElasticaClient;
use FHPlatform\ClientBundle\Client\Index\IndexClient;

class QueryClient
{
    public function __construct(
        private readonly ElasticaClient $client,
        private readonly IndexClient $indexClient,
    ) {
    }

    public function getSearch(string $className, ?Query $query = null): Search
    {
        $index = $this->indexClient->getIndex($className);

        $search = new Search($this->client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        return $search;
    }

    public function getResults(string $className, ?Query $query = null, $limit = null, $offset = 0): array
    {
        $search = $this->getSearch($className, $query);

        $results = $this->scrollSearch($search, $limit, $offset);

        return $results;
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
