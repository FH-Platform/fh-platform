<?php

namespace FHPlatform\Component\Filter\Converter\Applicator;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use FHPlatform\Component\Filter\Converter\ApplicatorInterface;
use FHPlatform\Component\Filter\Converter\FilterInterface;

class SortApplicator implements ApplicatorInterface
{
    public function name(): string
    {
        return 'sort';
    }

    public function convert(Query $query, string $field, mixed $value): Query
    {
        $query->addSort([$field => $value]);

        return $query;
    }
}
