<?php

namespace FHPlatform\Component\Filter;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Filter\Converter\FilterInterface;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class FilterQuery
{
    public function __construct(
        private readonly iterable     $applicatorConverters,
        private readonly iterable     $filterConverters,
        private readonly QueryManager $queryManager,
    )
    {
    }

    public function search(Index $index, array $filters = [], $limit = 100, $offset = 0, string $type = QueryManager::TYPE_IDENTIFIERS): array
    {
        $queryBase = $this->applyApplicators($filters['applicators'] ?? []);
        $queryFilters = $this->applyFilters($filters['filters'] ?? []);

        $queryBase->setQuery($queryFilters);

        return $this->queryManager->getResults($index, $queryBase, $limit, $offset, $type);
    }

    private function applyFilters(array $filters): BoolQuery
    {
        $queryFilters = new BoolQuery();

        foreach ($filters as $field => $filter) {
            foreach ($filter as $operator => $value) {
                $matched = false;
                foreach ($this->filterConverters as $filterConverter) {
                    /* @var FilterInterface $filter */

                    if ($filterConverter->name() === $operator) {
                        $matched = true;
                        $query = $filterConverter->convert($queryFilters, $field, $value);
                    }
                }

                if (false === $matched) {
                    //TODO
                    throw new \Exception('Filter "' . $operator . '"  does not exists');
                }
            }
        }

        return $queryFilters;
    }

    private function applyApplicators(array $applicators): Query{
        $queryBase = new Query();

        foreach ($applicators as $field => $applicator) {
            foreach ($applicator as $operator => $value) {
                $matched = false;
                foreach ($this->applicatorConverters as $applicatorConverter) {
                    /* @var FilterInterface $filter */

                    if ($applicatorConverter->name() === $operator) {
                        $matched = true;
                        $query = $applicatorConverter->convert($queryBase, $field, $value);
                    }
                }

                if (false === $matched) {
                    //TODO
                    throw new \Exception('Applicator "' . $operator . '" does not exists');
                }
            }
        }

        return $queryBase;
    }
}
