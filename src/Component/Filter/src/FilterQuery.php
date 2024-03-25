<?php

namespace FHPlatform\Component\Filter;

use Elastica\Query\BoolQuery;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Filter\Converter\FilterInterface;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class FilterQuery
{
    public function __construct(
        private readonly iterable $filterConverters,
        private readonly QueryManager $queryManager,
    ) {
    }

    public function search(Index $index, array $filters = [], $limit = 100, $offset = 0, string $type = QueryManager::TYPE_IDENTIFIERS): array
    {
        $query = new BoolQuery();

        foreach ($filters as $field => $filter) {
            foreach ($filter as $operator => $value) {
                $matched = false;
                foreach ($this->filterConverters as $filterConverter) {
                    /* @var FilterInterface $filter */

                    if ($filterConverter->name() === $operator) {
                        $matched = true;
                        $query = $filterConverter->convert($query, $field, $value);
                    }
                }

                if (false === $matched) {
                    throw new \Exception('Filter does not exists');
                }
            }
        }

        return $this->queryManager->getResults($index, $query, 10, 0, $type);
    }
}
