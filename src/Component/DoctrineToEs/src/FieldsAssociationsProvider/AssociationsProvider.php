<?php

namespace FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider;

use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\ConfigProvider\ConfigAssociationsProvider;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\DoctrineProvider\DoctrineAssociationsProvider;

class AssociationsProvider
{
    public function __construct(
        private readonly DoctrineAssociationsProvider $doctrineAssociationsProvider,
        private readonly ConfigAssociationsProvider $configAssociationsProvider,
    ) {
    }

    public function provide(string $className, array $config): array
    {
        $doctrineAssociations = $this->doctrineAssociationsProvider->provide($className);
        $configAssociations = $this->configAssociationsProvider->provide($className, $config);

        $associations = [];

        foreach ($doctrineAssociations as $doctrineAssociation) {
            $type = $doctrineAssociation['type'];
            $fieldName = $doctrineAssociation['fieldName'];
            $columnName = $doctrineAssociation['columnName'];
            $targetEntity = $doctrineAssociation['targetEntity'];
            $inversedBy = $doctrineAssociation['inversedBy'];

            // skip not included association
            $configAssociation = $configAssociations[$fieldName] ?? null;
            unset($configAssociations[$fieldName]);
            if (null === $configAssociation) {
                continue;
            }

            if (8 === $type and $targetEntity === $className) {
                // MANY-TO-MANY self-referencing
                $inversedBy = $fieldName;
            }

            if (!$inversedBy) {
                throw new \Exception('inversedBy for association "'.$fieldName.'" in class "'.$className.'" can not be found');
            }

            // check and set getter
            $getter = 'get'.ucfirst($fieldName);
            if (!method_exists($className, $getter)) {
                throw new \Exception('Getter "'.$getter.'" for association "'.$fieldName.'" in class "'.$className.'" can not be found');
            }

            // doctrine types = many-to-many = 8, one-to-many = 4, many-to-one = 2, one-to-one = 1
            $typeEs = (in_array($type, [1, 2]) ? 'object' : 'nested');

            // add additional data
            $associations[] = array_merge($doctrineAssociation, [
                'getter' => $getter,
                'configAssociation' => $configAssociation,
                'typeEs' => $typeEs,
            ]);
        }

        // throw error if there is association in config which can not be found in doctrine mapping
        if (count($configAssociations) > 0) {
            $configAssociation = array_key_first($configAssociations);
            throw new \Exception('Association "'.$configAssociation.'" in class "'.$className.'"  not found ');
        }

        return $associations;
    }
}
