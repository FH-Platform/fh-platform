<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\Decorator\DecoratorEntityRelated;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;

class EntityRelatedDecorator extends DecoratorEntityRelated
{
    public function __construct(
        private readonly UpdatingMapBuilder $updatingMapBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        $connections = $this->connectionsBuilder->build();

        $connectionsArray = [];
        foreach ($connections as $connection){
            foreach ($connection->getIndexes() as $index){
                $config = $index->getConfigAdditional()['doctrine_to_es'];

                if($config !== null){
                    $connectionsArray[$connection->getName()][$index->getClassName()] = $config;
                }
            }
        }

        $updatingMap = $this->updatingMapBuilder->build($connectionsArray);

        $entitiesRelated = $this->entitiesRelatedBuilder->build($entity, $updatingMap, []);

        return array_merge($entitiesRelated, $this->updatingMapBuilder->build($connections));
    }
}
