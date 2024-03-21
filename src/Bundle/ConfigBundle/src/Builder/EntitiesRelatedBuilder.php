<?php

namespace FHPlatform\Bundle\ConfigBundle\Builder;

use FHPlatform\Bundle\ConfigBundle\Config\ConfigProvider;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderBaseInterface;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
    ) {
    }

    public function build($entity): array
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($entity, $decorators);

        // return
        return $entitiesRelated;
    }

    public function fetchClassNamesRelated(): array
    {
        $classNames = [];
        foreach ($this->configProvider->getProvidersEntityRelated() as $provider) {
            $classNames[$provider->getClassName()] = $provider->getClassName();
        }

        return $classNames;
    }

    /** @param  DecoratorEntityRelatedInterface[] $decorators */
    private function decorateEntitiesRelated(mixed $entity, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($entity, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
