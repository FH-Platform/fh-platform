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
        $nullExists = false;
        foreach ($value as $k => $value2) {
            if (null === $value2) {
                unset($value[$k]);
                $nullExists = true;
            }
        }

        $termsQuery = new Terms($field, $value);

        if ($nullExists) {
            $boolQueryExists = new BoolQuery();
            $existsQuery = new Exists($field);
            $boolQueryExists->addMustNot($existsQuery);

            $boolQueryWrapper = new BoolQuery();
            $boolQueryWrapper->addShould($termsQuery);
            $boolQueryWrapper->addShould($boolQueryExists);

            $query->addShould($boolQueryWrapper);

            return $query;
        }

        $query->addShould($termsQuery);

        return $query;
    }
}
