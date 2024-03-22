<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Persistence;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\Bundle\PersistenceBundle\Persistence\PersistenceInterface;

class PersistenceDoctrine implements PersistenceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getIdentifierName(mixed $entity): ?string
    {
        if (is_string($entity)) {
            $className = $this->getRealClass($entity);
        } else {
            $className = $this->getRealClass(get_class($entity));
        }

        if ($this->entityManager->getMetadataFactory()->isTransient($className)) {
            return null;
        }

        $meta = $this->entityManager->getClassMetadata($className);

        return $meta->getSingleIdentifierFieldName();
    }

    public function getIdentifierValue(mixed $entity): mixed
    {
        $identifier = $this->getIdentifierName($entity);

        if (!$identifier) {
            return null;
        }

        $getter = 'get'.ucfirst($identifier);

        return $entity->{$getter}();
    }

    public function refresh(mixed $entity): mixed
    {
        if (!$entity) {
            return null;
        }

        $className = $this->getRealClass($entity::class);

        $identifierName = $this->getIdentifierName($entity);
        $identifierValue = $this->getIdentifierValue($entity);

        $repository = $this->entityManager->getRepository($className);
        $entity = $repository->findOneBy([$identifierName => $identifierValue]);

        if ($entity) {
            $this->entityManager->refresh($entity);

            if ($entity) {
                return $entity;
            }
        }

        return null;
    }

    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed
    {
        $className = $this->getRealClass($className);

        $repository = $this->entityManager->getRepository($className);

        $identifierName = $this->getIdentifierName($className);

        $entity = $repository->findOneBy([$identifierName => $identifierValue]);

        if ($entity) {
            $this->entityManager->refresh($entity);

            if ($entity) {
                return $entity;
            }
        }

        return null;
    }

    public function getRealClass(string $className): string
    {
        $className = ClassUtils::getRealClass($className);

        if ($this->entityManager->getMetadataFactory()->isTransient($className)) {
            return $className;
        }

        $className = $this->entityManager->getClassMetadata($className)->rootEntityName;

        return $className;
    }

    public function getAllIds(string $className): array
    {
        // TODO add to config
        $maxResults = 10;

        $firstResult = 0;
        $identifiers = [];
        while (true) {
            // TODO order by identifier
            $query = $this->entityManager->createQuery(
                'SELECT e FROM '.$className.' e '.
                ' ORDER BY e.id DESC'
            )
                ->setMaxResults($maxResults)
                ->setFirstResult($firstResult);

            $entitiesBatch = $query->getResult();

            // when we reach batch where there are no results break while(true)
            if (0 === count($entitiesBatch)) {
                break;
            }

            $identifiersBatch = [];
            foreach ($entitiesBatch as $entityBatch) {
                $identifier = $this->getIdentifierValue($entityBatch);

                // store identifiers from each batch
                // TODO detect $shouldBeIndexed
                $identifiers[] = $identifier;
            }

            // increase firstResult(offset)
            $firstResult += $maxResults;

            $this->entityManager->clear();
        }

        return $identifiers;
    }
}
