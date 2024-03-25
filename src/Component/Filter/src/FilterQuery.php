<?php

namespace FHPlatform\Component\Filter;

use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Terms;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class FilterQuery
{
    public function __construct(
        private readonly QueryManager $queryManager
    ) {
    }

    public function search(Index $index, array $filters = [], $limit = 100, $offset = 0, string $type = QueryManager::TYPE_IDENTIFIERS): array
    {
        $results = [];

        $queryFilter = new BoolQuery();

        foreach ($filters as $field => $filter) {
            foreach ($filter as $operator => $value) {
                if ('equals' === $operator) {
                    $matchQuery = new MatchQuery();
                    $matchQuery->setField($field, $value);
                    $queryFilter->addMust($matchQuery);
                } elseif ('in' === $operator) {
                    $terms = new Terms($field, $value);
                    $queryFilter->addMust($terms);
                }
            }
        }

        return $this->queryManager->getResults($index, $queryFilter, 10, 0, $type);
    }
}
