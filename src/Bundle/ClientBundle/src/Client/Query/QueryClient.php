<?php

namespace FHPlatform\ClientBundle\Client\Query;

use Elastica\Query;
use Elastica\Result;
use Elastica\Search;
use FHPlatform\ClientBundle\Client\Index\IndexClient;
use FHPlatform\ClientBundle\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;

class QueryClient
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
        private readonly IndexClient $indexClientNew,
    ) {
    }

    public function getSearch(Index $index, ?Query $query = null): Search
    {
        $client = $this->connectionFetcher->fetch($index->getConnection());

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

        $search = new Search($client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        return $search;
    }

    public function getResults(Index $index, ?Query $query = null, $limit = null, $offset = 0): array
    {
        $search = $this->getSearch($index, $query);

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
