<?php

namespace FHPlatform\Component\PersistenceDoctrine\Persistence;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class PersistenceDoctrine implements PersistenceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getIdentifierName(mixed $entity): ?string
    {
        if (!$entity) {
            return null;
        }

        if (is_string($entity)) {
            $className = $this->getRealClassName($entity);
        } else {
            $className = $this->getRealClassName(get_class($entity));
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

        $className = $this->getRealClassName($entity::class);

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
        $className = $this->getRealClassName($className);

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

    public function getEntities(string $className, array $identifierValues): array
    {
        $identifierName = $this->getIdentifierName($className);

        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('o')
            ->from($className, 'o');

        $queryBuilder
            ->andWhere('o.'.$identifierName.' IN(:identifiers)')
            ->setParameter('identifiers', $identifierValues);

        if (!empty($identifierValues)) {
            $dbDriver = $this->entityManager->getConnection()->getParams()['driver'];

            if ('pdo_sqlite' !== $dbDriver) {
                $this->addSortQueryForMysql($queryBuilder, $identifierValues);
            } else {
                $this->addSortQueryForSqlite($queryBuilder, $identifierValues);
            }
        }

        return $queryBuilder->getQuery()->execute();
    }

    public function getRealClassName(string $className): string
    {
        $className = ClassUtils::getRealClass($className);

        if ($this->entityManager->getMetadataFactory()->isTransient($className)) {
            return $className;
        }

        $className = $this->entityManager->getClassMetadata($className)->rootEntityName;

        return $className;
    }

    public function getAllIdentifierValues(string $className): array
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

    public function isEntity(string $className): bool
    {
        if ($this->entityManager->getMetadataFactory()->isTransient($className)) {
            return true;
        }

        return false;
    }

    // TODO move somewhere
    private function addSortQueryForMysql($queryBuilder, $identifiers): void
    {
        $queryBuilder->orderBy('FIELD(o.id,'.implode(',', $identifiers).')');
    }

    private function addSortQueryForSqlite($queryBuilder, $identifiers): void
    {
        $sort = ' CASE ';

        $counter = 1;
        foreach ($identifiers as $identifier) {
            $sort .= ' WHEN o.id = '.$identifier.'  THEN '.$counter++.' ';
        }
        $sort .= ' ELSE 1 END ';

        $queryBuilder->addOrderBy($sort);
    }
}
