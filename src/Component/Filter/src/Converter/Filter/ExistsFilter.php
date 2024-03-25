<?php

namespace FHPlatform\Component\Filter\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use FHPlatform\Component\Filter\Converter\FilterInterface;

class ExistsFilter implements FilterInterface
{
    public function name(): string
    {
        return 'exists';
    }

    public function convert(BoolQuery $query, string $field, mixed $value): AbstractQuery
    {
        $existsQuery = new Exists($field);
        $query->addMust($existsQuery);

        return $query;
    }
}
