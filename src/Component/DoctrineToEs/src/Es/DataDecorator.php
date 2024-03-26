<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntity;
use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Mapper\AssociationsProvider;
use FHPlatform\Component\DoctrineToEs\Mapper\FieldsProvider;

class DataDecorator extends DecoratorEntity
{
    public function __construct(
        private readonly DataBuilder $dataBuilder,
    )
    {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        $config = [];

        $config = $index->getConfigAdditional()['doctrine_to_es'] ?? null;
        $data = array_merge($data, $this->dataBuilder->build($index, $entity, $config));

        return $data;
    }
}
