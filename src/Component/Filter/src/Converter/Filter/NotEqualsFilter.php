<?php

namespace FHPlatform\Component\Filter\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use FHPlatform\Component\Filter\Converter\FilterInterface;

class NotEqualsFilter implements FilterInterface
{
    public function name(): string
    {
        return 'not_equals';
    }

    public function convert(BoolQuery $query, string $field, mixed $value): AbstractQuery
    {
        $matchQuery = new MatchQuery();
        $matchQuery->setField($field, $value);
        $query->addMustNot($matchQuery);

        return $query;
    }
}
