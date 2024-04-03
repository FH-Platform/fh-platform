<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;

class NotExistsFilter implements FilterInterface
{
    public function name(): string
    {
        return 'not_exists';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $existsQuery = new Exists($field);
        $query->addMustNot($existsQuery);

        return $query;
    }
}
