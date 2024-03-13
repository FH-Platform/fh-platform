<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorEntity;
use FHPlatform\ConfigBundle\Tests\Fetcher\Util\Entity\User;

class DecoratorEntity_First extends DecoratorEntity
{
    public function priority(): int
    {
        return 1;
    }

    public function getEntityData(Index $index, mixed $entity, array $data, array $mapping): array
    {
        $data['decorator_entity_data_level_1'] = 1;

        return $data;
    }

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        if ($entity instanceof User) {
            return true;
        }

        return $shouldBeIndexed;
    }
}
