<?php

namespace FHPlatform\Component\PersistenceEloquent\Persistence;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use Illuminate\Database\Eloquent\Model;

class PersistenceEloquent implements PersistenceInterface
{
    public function getIdentifierName(mixed $entity): ?string
    {
        /** @var  Model $entity */
        return $entity->getKeyName();
    }

    public function getIdentifierValue(mixed $entity): mixed
    {
        /** @var  Model $entity */
        return $entity->{$this->getIdentifierName($entity)};
    }

    public function refresh(mixed $entity): mixed
    {
        /** @var  Model $entity */
        return ($entity::class)->find($this->getIdentifierValue($entity));
    }

    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed
    {
        return ($className)->find($this->getIdentifierValue($identifierValue));
    }

    public function getEntities(string $className, array $identifiers): array
    {
        //TODO sort
        return ($className)->find([$this->getIdentifierName($className) => $identifiers]);
    }

    public function getRealClass(string $className): string
    {
        return $className;
    }

    public function getAllIds(string $className): array
    {
        //TODO batch
        return ($className)->find([])->pluck($this->getIdentifierName($className));
    }
}
