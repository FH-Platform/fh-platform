<?php

namespace FHPlatform\Component\FilterToEsDsl\Converter\Filter;

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\Exists;
use Elastica\Query\Terms;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;

class InFilter implements FilterInterface
{
    public function name(): string
    {
        return 'in';
    }

    public function convert(BoolQuery $query, string $field, mixed $value, ?array $mappingItem): AbstractQuery
    {
        $nullExists = false;
        foreach ($value as $k => $value2) {
            // TODO escape
            if ('null' === $value2) {
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
