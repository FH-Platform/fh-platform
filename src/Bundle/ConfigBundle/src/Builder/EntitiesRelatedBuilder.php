<?php

namespace FHPlatform\ConfigBundle\Builder;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderBaseInterface;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly ConfigProvider $taggedProvider,
    ) {
    }

    public function build($entity): array
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($entity, $decorators);

        // return
        return $entitiesRelated;
    }

    public function fetchClassNamesRelated(): array
    {
        $classNames = [];
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
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
