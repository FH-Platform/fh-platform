<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use FHPlatform\ConfigBundle\DTO\EntityRelated;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntityRelated;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class EntityRelatedFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetch($entity): EntityRelated
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entity_related
        $entitiesRelated = $this->decorateEntitiesRelated($entity, $decorators);

        // return
        return new EntityRelated($entity, $entitiesRelated);
    }

    /** @param  DecoratorEntityRelated[] $decorators */
    private function decorateEntitiesRelated(mixed $entity, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($entity, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
