<?php

namespace FHPlatform\Component\PersistenceEloquent;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentPersistence implements PersistenceInterface
{
    public function getIdentifierName(mixed $entity): ?string
    {
        if (is_string($entity)) {
            $entity = new $entity();
        }

        /* @var  Model $entity */
        return $entity->getKeyName();
    }

    public function getIdentifierValue(mixed $entity): mixed
    {
        /* @var  Model $entity */
        return $entity->{$this->getIdentifierName($entity)};
    }

    public function refresh(mixed $entity): mixed
    {
        return (new ($entity::class))->find($this->getIdentifierName($entity));
    }

    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed
    {
        return (new $className())->find($identifierValue);
    }

    public function getEntities(string $className, array $identifierValues): array
    {
        // TODO sort
        $results = (new $className())->whereIn($this->getIdentifierName($className), $identifierValues)->get();

        $resultsArray = [];
        foreach ($results as $result) {
            $resultsArray[] = $result;
        }

        return $resultsArray;
    }

    public function getRealClassName(string $className): string
    {
        return $className;
    }

    public function getAllIdentifierValues(string $className): array
    {
        // TODO batch
        $entities = (new $className())->all();

        $identifiers = [];
        foreach ($entities as $entity) {
            $identifiers[] = $entity->{$this->getIdentifierName($entity)};
        }

        return $identifiers;
    }

    public function isEntity(string $className): bool
    {
        // TODO

        return true;
    }
}
