<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use FHPlatform\Bundle\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\Bundle\PersistenceBundle\EventDispatcher\EventDispatcher;
use FHPlatform\Bundle\PersistenceDoctrineBundle\Helper\DoctrineHelper;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::preRemove)]
#[AsDoctrineListener(event: Events::postRemove)]
#[AsDoctrineListener(event: Events::postFlush)]
class DoctrineListener
{
    protected array $eventsRemove = [];

    public function __construct(
        private readonly DoctrineHelper  $doctrineHelper,
        private readonly EventDispatcher $eventsManager,
    ) {
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntityEvent::TYPE_CREATE);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntityEvent::TYPE_UPDATE);
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntityEvent::TYPE_DELETE);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->addEntity($args, ChangedEntityEvent::TYPE_DELETE);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        $this->eventsManager->flushEvent();
    }

    private function addEntity(PostPersistEventArgs|PostUpdateEventArgs|PostRemoveEventArgs|PreRemoveEventArgs $args, string $type): void
    {
        $entity = $args->getObject();

        $className = $this->doctrineHelper->getRealClass($entity::class);
        $identifierValue = $this->doctrineHelper->getIdentifierValue($entity);

        $changedFields = [$this->doctrineHelper->getIdentifierName($className)];

        // on pre remove store identifier and return
        if ($args instanceof PreRemoveEventArgs) {
            $this->eventsRemove[spl_object_id($entity)] = $identifierValue;

            // we must dispatch PreDeleteEntity immediately, because related entities for deleted entity can be fetched only at this point not later on postRemove
            $this->eventsManager->dispatchPreDeleteEntityEvent($className, $identifierValue);

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

        $this->eventsManager->addEvent($className, $identifierValue, $type, $changedFields);
    }
}
