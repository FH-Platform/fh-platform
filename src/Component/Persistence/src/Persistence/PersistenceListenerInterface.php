<?php

namespace FHPlatform\Component\Persistence\Persistence;

use Doctrine\ORM\Event\PostPersistEventArgs;

interface PersistenceListenerInterface
{
    //event post create from persistence implementation
    public function eventPostCreate(string $className, mixed $identifierValue, array $changedFields): void;

    //event post update from persistence implementation
    public function eventPostUpdate(string $className, mixed $identifierValue, array $changedFields): void;

    //event post delete from persistence implementation
    public function eventPostDelete(string $className, mixed $identifierValue, array $changedFields): void;

    //event pre delete from persistence implementation (it is needed to update related entities which are no longer available after postDelete)
    //for example if you have in search engine user->roles->name and role is deleted we must detect all users which have that role so that we can update
    //all users in search engine
    public function eventPreDelete(string $className, mixed $identifierValue, array $changedFields): void;
}
