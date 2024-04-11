<?php

namespace FHPlatform\Component\Config\Builder;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\DTO\Connection;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    public function build(mixed $entity, $changedFields = []): array
    {
        // TODO remove all -> $entity::class
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // TODO
        $connections = $this->connectionsBuilder->build();
        $connection = $connections[0];

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($connection, $entity, $changedFields, $decorators);

        // return
        return $entitiesRelated;
    }

    /** @param DecoratorEntityRelatedInterface[] $decorators */
    private function decorateEntitiesRelated(Connection $connection, mixed $entity, array $changedFields, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($connection, $entity, $changedFields, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
