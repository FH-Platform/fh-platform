<?php

namespace FHPlatform\Component\Persistence\Persistence;

// each persistence implementation (doctrine, eloquent, etc.) must have
// 1.implementation of that interface for required interacting with persistence
// 2.listener which triggers events from EventManager (postCreate, postUpdate, postDelete, preDelete and flush)
interface PersistenceInterface
{
    // detect if given className is part of the persistence classes (entities)
    public function isEntityClassName(string $className): bool;

    // give me an identifier name for given entity, for example: id, uuid, etc.
    public function getIdentifierName(mixed $entity): ?string;

    // give me an identifier value for given entity, for example 1, or uuid value
    public function getIdentifierValue(mixed $entity): mixed;

    // give me a fresh entity, re-fetch from DB
    public function refresh(mixed $entity): mixed;

    // give me a fresh entity, re-fetch from DB, by entity className and identifier value
    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed;

    // get all ids from DB for given entity className (it should be internally fetched in batches and then return the array of all identifiers)
    public function getAllIdentifierValues(string $className): array;

    // fetch entities by given class name and identifier values, it should also implement sort by exact order of given identifier values
    public function getEntities(string $className, array $identifierValues): array;

    // some persistence's like doctrine sometimes return a ghost objects or proxies so that method will return reals class name (eloquent will never return proxies)
    public function getRealClassName(string $className):  ?string;
}
