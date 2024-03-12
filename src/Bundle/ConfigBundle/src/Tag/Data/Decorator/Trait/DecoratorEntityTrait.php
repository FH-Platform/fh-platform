<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait;

trait DecoratorEntityTrait
{
    public function getEntityData(mixed $entity, array $data): array
    {
        return $data;
    }

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        return $shouldBeIndexed;
    }
}
