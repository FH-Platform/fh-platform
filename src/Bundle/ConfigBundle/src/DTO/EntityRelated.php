<?php

namespace FHPlatform\ConfigBundle\DTO;

class EntityRelated
{
    public function __construct(
        private readonly mixed $entity,
        private readonly array $entitiesRelated,
    ) {
    }

    public function getEntity(): mixed
    {
        return $this->entity;
    }

    public function getEntitiesRelated(): array
    {
        return $this->entitiesRelated;
    }
}
