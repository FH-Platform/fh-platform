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
    ) {
    }

    public function build(Connection $connection, mixed $entity, string $type, $changedFields): array
    {
        // TODO remove all -> $entity::class
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($connection, $entity, $type, $changedFields, $decorators);

        // return
        return $entitiesRelated;
    }

    /** @param  DecoratorEntityRelatedInterface[] $decorators */
    private function decorateEntitiesRelated(Connection $connection, mixed $entity, string $type, array $changedFields, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($connection, $entity, $type, $changedFields, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
