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
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;

class DoctrineListener
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
        $this->eventHelper->flushEvent();
    }

    private function addEntity(PostPersistEventArgs|PostUpdateEventArgs|PostRemoveEventArgs|PreRemoveEventArgs $args, string $type): void
    {
        $entity = $args->getObject();

        $className = $this->persistenceDoctrine->getRealClassName($entity::class);
        $identifierValue = $this->persistenceDoctrine->getIdentifierValue($entity);

        $changedFields = [$this->persistenceDoctrine->getIdentifierName($className)];

        // on pre remove store identifier and return
        if ($args instanceof PreRemoveEventArgs) {
            $this->eventsRemove[spl_object_id($entity)] = $identifierValue;

            // we must dispatch PreDeleteEntity immediately, because related entities for deleted entity can be fetched only at this point not later on postRemove
            $this->eventHelper->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE_PRE, $changedFields, true);

            return;
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

        $this->eventHelper->addEntity($className, $identifierValue, $type, $changedFields, false);
    }
}
