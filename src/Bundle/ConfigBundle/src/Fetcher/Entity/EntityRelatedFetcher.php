<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class EntityRelatedFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetchClassNamesRelated(): array
    {
        $classNames = [];
        foreach ($this->taggedProvider->getProvidersEntityRelated() as $provider) {
            $classNames[$provider->getClassName()] = $provider->getClassName();
        }

        return $classNames;
    }

    public function fetchEntitiesRelated($entity): array
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
