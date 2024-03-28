<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\FilterToEsDsl;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Terms;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;

class NotInFilter implements FilterInterface
{
    public function name(): string
    {
        return 'not_in';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
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

            $boolQueryWrapper = new BoolQuery();
            $boolQueryWrapper->addMust($existsQuery);
            $boolQueryWrapper->addMustNot($termsQuery);

            $query->addMust($boolQueryWrapper);

            return $query;
        }

        $query->addMustNot($termsQuery);

        return $query;
    }
}
