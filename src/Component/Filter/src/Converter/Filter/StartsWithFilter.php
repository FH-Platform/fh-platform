<?php

namespace FHPlatform\Component\Filter\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchPhrasePrefix;
use FHPlatform\Component\Filter\Converter\FilterInterface;

class StartsWithFilter implements FilterInterface
{
    public function name(): string
    {
        return 'starts_with';
    }

    public function convert(BoolQuery $query, string $field, mixed $value): AbstractQuery
    {
        $matchPhrasePrefixQuery = new MatchPhrasePrefix();
        $matchPhrasePrefixQuery->setField($field, $value);

        $query->addMust($matchPhrasePrefixQuery);

        return $query;
    }
}
