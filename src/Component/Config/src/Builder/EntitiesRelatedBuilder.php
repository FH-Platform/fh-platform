<?php

namespace FHPlatform\Component\Config\Builder;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
    ) {
    }

    public function build(mixed $entity, $changedFields): array
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($entity, $changedFields, $decorators);

        // return
        return $entitiesRelated;
    }

    /** @param  DecoratorEntityRelatedInterface[] $decorators */
    private function decorateEntitiesRelated(mixed $entity, array $changedFields, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($entity, $changedFields, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
