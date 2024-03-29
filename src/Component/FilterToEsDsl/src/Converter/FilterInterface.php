<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;

interface FilterInterface
{
    public function name(): string;

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery;
}
