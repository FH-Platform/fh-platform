<?php

namespace FHPlatform\Component\PersistenceEloquent\Persistence;

use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use Illuminate\Database\Eloquent\Model;

class PersistenceEloquent implements PersistenceInterface
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

    public function getEntities(string $className, array $identifiers): array
    {
        // TODO sort
        $results = (new $className())->whereIn($this->getIdentifierName($className), $identifiers)->get();

        $resultsArray = [];
        foreach ($results as $result) {
            $resultsArray[] = $result;
        }

        return $resultsArray;
    }

    public function getRealClass(string $className): string
    {
        return $className;
    }

    public function getAllIds(string $className): array
    {
        // TODO batch
        $entities = (new $className())->all();

        $identifiers = [];
        foreach ($entities as $entity) {
            $identifiers[] = $entity->{$this->getIdentifierName($entity)};
        }

        return $identifiers;
    }
}
