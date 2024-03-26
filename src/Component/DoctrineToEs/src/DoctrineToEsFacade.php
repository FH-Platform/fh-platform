<?php

namespace FHPlatform\Component\DoctrineToEs;

use FHPlatform\Component\DoctrineToEs\Provider\DataProvider;
use FHPlatform\Component\DoctrineToEs\Provider\EntitiesRelatedProvider;
use FHPlatform\Component\DoctrineToEs\Provider\MappingProvider;
use FHPlatform\Component\DoctrineToEs\Provider\UpdatingMapProvider;

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
        private readonly DataProvider $dataProvider,
        private readonly MappingProvider $mappingProvider,
        private readonly EntitiesRelatedProvider $entitiesRelatedProvider,
        private readonly UpdatingMapProvider $updatingMapProvider,
    ) {
    }

    public function fetchData(string $className, $entity, $config, $sameLevel = true): array
    {
        return $this->dataProvider->provide($className, $entity, $config, $sameLevel);
    }

    public function fetchMapping(string $className, $config, $sameLevel = true): array
    {
        return $this->mappingProvider->provide($className, $config, $sameLevel);
    }

    public function fetchUpdatingMap($classNamesConfig): array
    {
        return $this->updatingMapProvider->provide($classNamesConfig)[0];
    }

    public function fetchUpdatingMapReversed($classNamesConfig): array
    {
        return $this->updatingMapProvider->provide($classNamesConfig)[1];
    }

    public function fetchEntitiesRelated($entity, $updatingMap, $changedFields): array
    {
        return $this->entitiesRelatedProvider->provide($entity, $updatingMap, $changedFields);
    }
}
