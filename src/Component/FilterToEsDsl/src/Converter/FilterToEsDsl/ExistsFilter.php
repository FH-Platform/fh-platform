<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\FilterToEsDsl;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;

class ExistsFilter implements FilterInterface
{
    public function name(): string
    {
        return 'exists';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $existsQuery = new Exists($field);
        $query->addMust($existsQuery);

        return $query;
    }
}
