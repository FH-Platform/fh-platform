<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Trait;

trait DecoratorEntityTrait
{
    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        return $data;
    }

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        return $shouldBeIndexed;
    }
}
