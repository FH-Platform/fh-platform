<?php

namespace FHPlatform\UtilBundle\Helper;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;

class EntityHelper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws MappingException
     */
    public function getIdentifierName(mixed $entity): string
    {
        if (is_string($entity)) {
            $className = $this->getRealClass($entity);
        } else {
            $className = $this->getRealClass(get_class($entity));
        }

        $meta = $this->entityManager->getClassMetadata($className);

        return $meta->getSingleIdentifierFieldName();
    }

    public function getIdentifierValue(mixed $entity): mixed
    {
        $identifier = $this->getIdentifierName($entity);

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
        $className = $this->entityManager->getClassMetadata($className)->rootEntityName;

        return $className;
    }
}
