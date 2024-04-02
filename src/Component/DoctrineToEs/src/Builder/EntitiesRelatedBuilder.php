<?php

namespace FHPlatform\Component\DoctrineToEs\Builder;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function build($entity, array $doctrineUpdatingMap, string $type, array $changedFields): array
    {
        $className = ClassUtils::getClass($entity);

        $entitiesRelated = [];

        $doctrineUpdatingMapForEntity = $doctrineUpdatingMap[$className] ?? [];

        foreach ($doctrineUpdatingMapForEntity as $updatingMapForEntityRow) {
            $associationString = $updatingMapForEntityRow['relations'];
            $changedFieldsForEs = $updatingMapForEntityRow['changed_fields'];

            // detect if field from doctrine-to-es config is changed
            $identifierName = $this->persistence->getIdentifierName($className);

            if (ChangedEntity::TYPE_UPDATE === $type and count($changedFieldsForEs) > 0 and count($changedFields) > 0) {
                $isChangedAnyEsField = !empty(array_intersect($changedFieldsForEs, $changedFields)) || !empty($changedFields[$identifierName]);
                if (!$isChangedAnyEsField) {
                    continue;
                }
            }

            $associations = explode('.', $associationString);

            $entitiesRelatedForEntityRow = [$entity];
            foreach ($associations as $key => $association) {
                $entitiesRelatedForEntityRow = $this->getEntitiesRelatedForAssociation($entitiesRelatedForEntityRow, $association);

                if ($key === array_key_last($associations)) {
                    $entitiesRelatedForEntityRowFiltered = $this->filterEntitiesRelated($className, $entitiesRelatedForEntityRow, $associationString);
                    $entitiesRelated = array_merge($entitiesRelated, $entitiesRelatedForEntityRowFiltered);
                }
            }
        }

        return $entitiesRelated;
    }

    private function getEntitiesRelatedForAssociation(array $entities, string $association): array
    {
        $associationGetter = 'get'.ucfirst($association);

        $entitiesRelated = [];
        foreach ($entities as $entity) {
            if (!$entity = $this->persistence->refresh($entity)) {
                return [];
            }

            // get entity or related entities in array, depends on association type
            $associationGetterResult = $entity->{$associationGetter}();

            if ($associationGetterResult instanceof Collection) {
                $entitiesRelated = $associationGetterResult->toArray();
            } else {
                $entitiesRelated[] = $associationGetterResult;
            }
        }

        return $entitiesRelated;
    }

    private function filterEntitiesRelated(string $className, array $entities, string $associationString): array
    {
        $entitiesFiltered = [];

        foreach ($entities as $entity) {
            if (!$entity = $this->persistence->refresh($entity)) {
                continue;
            }

            // use hash className + associationString to prevent adding the same entity
            $identifierValue = $this->persistence->getIdentifierValue($entity);

            $entitiesFiltered[$associationString][$identifierValue] = $entity;
        }

        return $entitiesFiltered;
    }
}
