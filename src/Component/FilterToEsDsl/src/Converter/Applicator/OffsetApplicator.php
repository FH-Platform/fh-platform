<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\Applicator;

use Elastica\Query;
use FHPlatform\Component\FilterToEsDsl\Converter\ApplicatorInterface;

class OffsetApplicator implements ApplicatorInterface
{
    public function name(): string
    {
        return 'offset';
    }

    public function convert(Query $query, mixed $value): Query
    {
        $query->setFrom((int) $value);

        return $query;
    }
}
