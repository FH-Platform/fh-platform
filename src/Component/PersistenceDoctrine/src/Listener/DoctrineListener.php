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
use FHPlatform\Component\Persistence\Event\EventManager;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;

class DoctrineListener
{
    protected array $eventsRemove = [];

    public function __construct(
        private readonly PersistenceDoctrine $persistenceDoctrine,
        private readonly EventManager        $eventManager,
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
        $this->processEvent($args, ChangedEntity::TYPE_CREATE);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->processEvent($args, ChangedEntity::TYPE_UPDATE);
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->processEvent($args, ChangedEntity::TYPE_DELETE);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->processEvent($args, ChangedEntity::TYPE_DELETE);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $this->eventManager->eventFlush();
    }

    private function processEvent(PostPersistEventArgs|PostUpdateEventArgs|PostRemoveEventArgs|PreRemoveEventArgs $args, string $type): void
    {
        $entity = $args->getObject();

        $className = $this->persistenceDoctrine->getRealClassName($entity::class);
        $identifierValue = $this->persistenceDoctrine->getIdentifierValue($entity);

        if ($args instanceof PostPersistEventArgs) {
            $this->eventManager->eventPostCreate($className, $identifierValue);
        } elseif ($args instanceof PostUpdateEventArgs) {
            $changedFields = array_keys($args->getObjectManager()->getUnitOfWork()->getEntityChangeSet($entity));

            $this->eventManager->eventPostUpdate($className, $identifierValue, $changedFields);
        } elseif ($args instanceof PostRemoveEventArgs) {
            $identifierValue = $this->eventsRemove[spl_object_id($entity)];

            $this->eventManager->eventPostDelete($className, $identifierValue);
        } elseif ($args instanceof PreRemoveEventArgs) {
            // on pre remove store identifier, so that we can later trigger event with that identifier
            $this->eventsRemove[spl_object_id($entity)] = $identifierValue;

            $this->eventManager->eventPreDelete($className, $identifierValue);
        }
    }
}
