<?php

namespace FHPlatform\Component\FilterToDsl\Converter\FilterToDsl;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchPhrasePrefix;
use FHPlatform\Component\FilterToDsl\Converter\FilterInterface;

class StartsWithFilter implements FilterInterface
{
    public function name(): string
    {
        return 'starts_with';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $matchPhrasePrefixQuery = new MatchPhrasePrefix();
        $matchPhrasePrefixQuery->setField($field, $value);

        $query->addMust($matchPhrasePrefixQuery);

        return $query;
    }
}
