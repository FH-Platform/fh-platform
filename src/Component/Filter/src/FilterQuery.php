<?php

namespace FHPlatform\Component\Filter;

use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\MatchQuery;
use Elastica\Query\Range;
use Elastica\Query\Terms;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class FilterQuery
{
    public function __construct(
        private readonly QueryManager $queryManager
    )
    {
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
                } elseif ('not_equals' === $operator) {
                    $matchQuery = new MatchQuery();
                    $matchQuery->setField($field, $value);
                    $queryFilter->addMustNot($matchQuery);
                } elseif ('in' === $operator) {
                    $terms = new Terms($field, $value);
                    $queryFilter->addMust($terms);
                } elseif ('not_in' === $operator) {
                    $terms = new Terms($field, $value);
                    $queryFilter->addMustNot($terms);
                } elseif ('lte' === $operator) {
                    $rangeQuery = new Range();
                    $rangeQuery->addField($field, ['lte' => $value]);
                    $queryFilter->addMust($rangeQuery);
                } elseif ('gte' === $operator) {
                    $rangeQuery = new Range();
                    $rangeQuery->addField($field, ['gte' => $value]);
                    $queryFilter->addMust($rangeQuery);
                } elseif ('exists' === $operator) {
                    $existsQuery = new Exists($field);
                    $queryFilter->addMust($existsQuery);
                } elseif ('not_exists' === $operator) {
                    $existsQuery = new Exists($field);
                    $queryFilter->addMustNot($existsQuery);
                }
            }
        }

        return $this->queryManager->getResults($index, $queryFilter, 10, 0, $type);
    }
}
