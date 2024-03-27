<?php

namespace FHPlatform\Component\Filter\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Terms;
use FHPlatform\Component\Filter\Converter\FilterInterface;

class InFilter implements FilterInterface
{
    public function name(): string
    {
        return 'in';
    }

    public function convert(BoolQuery $query, string $field, mixed $value): AbstractQuery
    {
        /*
         $boolQuery = new BoolQuery();

        if ($value !== null){
            $termsQuery = new Terms($field, $value);
            $query->addShould($termsQuery);
        }else{
            $existsQuery = new Exists($field);
            $query->addShould($existsQuery);
        }

        $boolQuery->addMust($query);

        return $boolQuery;

         */

        $termsQuery = new Terms($field, $value);
        $query->addMust($termsQuery);

        return $query;
    }
}
