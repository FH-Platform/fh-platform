<?php

namespace FHPlatform\Component\FilterToEsDsl;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;
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
        $queryBase = $this->applyApplicators($index, $filters['applicators'] ?? []);
        $queryFilters = $this->applyFilters($index, $filters['filters'] ?? []);

        $queryBase->setQuery($queryFilters);

        return $this->queryManager->getResults($index, $queryBase, $limit, $offset, $type);
    }

    private function applyApplicators(Index $index, array $applicatorsArray): Query
    {
        $queryBase = new Query();

        foreach ($applicatorsArray as $number => $applicators) {
            foreach ($applicators as $operator => $value) {
                $matched = false;
                foreach ($this->applicatorConverters as $applicatorConverter) {
                    /* @var FilterInterface $filter */

                    if ($applicatorConverter->name() === $operator) {
                        $matched = true;

                       $applicatorConverter->convert($queryBase, $value);
                    }
                }

                if (false === $matched) {
                    throw new \Exception('Applicator "' . $operator . '" does not exists');
                }
            }
        }

        return $queryBase;
    }

    private function applyFilters(Index $index, array $filtersArray): BoolQuery
    {
        $queryFilters = new BoolQuery();

        foreach ($filtersArray as $number => $filters) {
            foreach ($filters as $field => $filter) {
                foreach ($filter as $operator => $value) {
                    $matched = false;
                    foreach ($this->filterConverters as $filterConverter) {
                        /* @var FilterInterface $filter */

                        if ($filterConverter->name() === $operator) {
                            $matched = true;

                            $mappingItem = $this->fetchMapping($index, $field);
                            $filterConverter->convert($queryFilters, $field, $value, $mappingItem);
                        }
                    }

                    if (false === $matched) {
                        throw new \Exception('Filter "' . $operator . '"  does not exists');
                    }
                }
            }
        }

        return $queryFilters;
    }

    private function fetchMapping(Index $index, string $fields): ?array
    {
        $mapping = $index->getMapping();

        $fields = explode('.', $fields);

        $fieldsArray = [];
        foreach ($fields as $key => $field) {
            $fieldsArray[] = $field;
            $fieldsArray[] = 'properties';
        }
        unset($fieldsArray[array_key_last($fieldsArray)]);

        $output = [];
        $temp = &$mapping;
        foreach ($fieldsArray as $fieldArray) {
            $output = $temp[$fieldArray] ?? null;
        }

        return $output;
    }
}
