<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\Applicator;

use Elastica\Query;
use FHPlatform\Component\FilterToEsDsl\Converter\ApplicatorInterface;

class SortApplicator implements ApplicatorInterface
{
    public function name(): string
    {
        return 'sort';
    }

    public function convert(Query $query, mixed $value): Query
    {
        foreach ($value as $field => $valueItem) {
            $query->addSort([$field => $valueItem]);
        }

        return $query;
    }
}
