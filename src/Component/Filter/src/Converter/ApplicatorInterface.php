<?php

namespace FHPlatform\Component\Filter\Converter;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;

interface ApplicatorInterface
{
    public function name(): string;

    public function convert(Query $query, string $field, mixed $value): Query;
}
