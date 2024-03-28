<?php

namespace FHPlatform\Component\FilterToDsl\Converter\FilterToDsl;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use FHPlatform\Component\FilterToDsl\Converter\FilterInterface;

class GteFilter implements FilterInterface
{
    public function name(): string
    {
        return 'gte';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $rangeQuery = new Range();
        $rangeQuery->addField($field, ['gte' => $value]);
        $query->addMust($rangeQuery);

        return $query;
    }
}
