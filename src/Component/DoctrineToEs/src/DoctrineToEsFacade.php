<?php

namespace FHPlatform\Component\DoctrineToEs;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedProvider;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;

class DoctrineToEsFacade
{
    final public const DOCTRINE_TYPES_TO_ES_TYPES = [
        'boolean' => 'boolean',
        'integer' => 'integer',
        'bigint' => 'integer',
        'smallint' => 'integer',
        'float' => 'float',
        'decimal' => 'float',
        'string' => 'text',
        'text' => 'text',
        'date' => 'date',
        'datetime' => 'date',
    ];

    public function __construct(
        private readonly DataBuilder $dataProvider,
        private readonly MappingBuilder $mappingProvider,
        private readonly EntitiesRelatedProvider $entitiesRelatedProvider,
        private readonly UpdatingMapBuilder $updatingMapProvider,
    ) {
    }

    public function fetchMapping(Index $index, $config): array
    {
        return $this->mappingProvider->build($index, $config);
    }

    public function fetchData(Index $index, $entity, $config): array
    {
        return $this->dataProvider->build($index, $entity, $config);
    }

    public function fetchUpdatingMap($classNamesConfig): array
    {
        return $this->updatingMapProvider->build($classNamesConfig)[0];
    }

    public function fetchUpdatingMapReversed($classNamesConfig): array
    {
        return $this->updatingMapProvider->build($classNamesConfig)[1];
    }

    public function fetchEntitiesRelated($entity, $updatingMap, $changedFields): array
    {
        return $this->entitiesRelatedProvider->provide($entity, $updatingMap, $changedFields);
    }
}
