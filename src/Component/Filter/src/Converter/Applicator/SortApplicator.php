<?php

namespace FHPlatform\Component\Filter\Converter\Applicator;

use Elastica\Query;
use FHPlatform\Component\Filter\Converter\ApplicatorInterface;

class SortApplicator implements ApplicatorInterface
{
    public function name(): string
    {
        return 'sort';
    }

    public function convert(Query $query, string $field, mixed $value): Query
    {
        $query->addSort([$field => $value]);

        return $query;
    }
}
