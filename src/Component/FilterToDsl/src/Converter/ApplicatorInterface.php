<?php

namespace FHPlatform\Component\FilterToDsl\Converter;

use Elastica\Query;

interface ApplicatorInterface
{
    public function name(): string;

    public function convert(Query $query, string $field, mixed $value): Query;
}
