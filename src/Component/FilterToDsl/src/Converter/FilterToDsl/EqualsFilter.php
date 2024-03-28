<?php

namespace FHPlatform\Component\FilterToDsl\Converter\FilterToDsl;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use FHPlatform\Component\FilterToDsl\Converter\FilterInterface;

class EqualsFilter implements FilterInterface
{
    public function name(): string
    {
        return 'equals';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $matchQuery = new MatchQuery();
        $matchQuery->setField($field, $value);
        $query->addMust($matchQuery);

        return $query;
    }
}
