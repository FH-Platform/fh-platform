<?php

namespace FHPlatform\Component\PersistenceDoctrine\Listener;

use Doctrine\DBAL\Event\TransactionBeginEventArgs;
use Doctrine\DBAL\Event\TransactionRollBackEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Persistence\PersistenceListenerInterface;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;

class DoctrineListener implements PersistenceListenerInterface
{
    protected array $eventsRemove = [];

    public function __construct(
        private readonly PersistenceDoctrine $persistenceDoctrine,
        private readonly EventHelper $eventHelper,
    ) {
    }

    public function onTransactionBegin(TransactionBeginEventArgs $args): void
    {
        // dump('onTransactionBegin');
    }

    public function onTransactionRollBack(TransactionRollBackEventArgs $args): void
    {
        // dump('onTransactionRollBack');
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntity::TYPE_CREATE);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntity::TYPE_UPDATE);
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntity::TYPE_DELETE);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntity::TYPE_DELETE);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $this->eventHelper->eventFlush();
    }

    private function addEntity(PostPersistEventArgs|PostUpdateEventArgs|PostRemoveEventArgs|PreRemoveEventArgs $args, string $type): void
    {
        $entity = $args->getObject();

        $className = $this->persistenceDoctrine->getRealClassName($entity::class);
        $identifierValue = $this->persistenceDoctrine->getIdentifierValue($entity);

        $changedFields = [$this->persistenceDoctrine->getIdentifierName($className)];

        // on pre remove store identifier
        if ($args instanceof PreRemoveEventArgs) {
            $this->eventsRemove[spl_object_id($entity)] = $identifierValue;
        }

        // on post remove fetch stored identifier
        if ($args instanceof PostRemoveEventArgs) {
            $identifierValue = $this->eventsRemove[spl_object_id($entity)];
        }

        // TODO check, sometimes id, sometimes uuid
        // for persist and delete changedFields are ['id'], for update are calculated
        if ($args instanceof PostUpdateEventArgs) {
            $changedFields = array_keys($args->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity));
        }

        if($args instanceof PostPersistEventArgs){
            $this->eventPostCreate($className, $identifierValue, $changedFields);
        }elseif ($args instanceof  PostUpdateEventArgs){
            $this->eventPostUpdate($className, $identifierValue, $changedFields);
        }elseif ($args instanceof  PostRemoveEventArgs){
            $this->eventPostDelete($className, $identifierValue, $changedFields);
        }elseif ($args instanceof  PreRemoveEventArgs){
            $this->eventPreDelete($className, $identifierValue, $changedFields);
        }
    }

    public function eventPostCreate(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventHelper->addEntity($className, $identifierValue, ChangedEntity::TYPE_CREATE, $changedFields);
    }

    public function eventPostUpdate(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventHelper->addEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE, $changedFields);
    }

    public function eventPostDelete(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventHelper->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE, $changedFields);
    }

    public function eventPreDelete(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventHelper->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE_PRE, $changedFields);
        $this->eventHelper->eventFlush();
    }
}
