<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter;

use Elastica\Query;

interface ApplicatorInterface
{
    public function name(): string;

    public function convert(Query $query, mixed $value): Query;
}
