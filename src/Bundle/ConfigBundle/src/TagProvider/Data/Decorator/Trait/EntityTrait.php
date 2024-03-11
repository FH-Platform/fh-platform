<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait;

trait EntityTrait
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
