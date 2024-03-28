<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\Applicator;

use Elastica\Query;
use FHPlatform\Component\FilterToEsDsl\Converter\ApplicatorInterface;

class LimitApplicator implements ApplicatorInterface
{
    public function name(): string
    {
        return 'limit';
    }

    public function convert(Query $query, mixed $value): Query
    {
        $query->setSize((int) $value);

        return $query;
    }
}
