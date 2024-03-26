<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntityRelated;
use FHPlatform\Component\DoctrineToEs\Es\Helper\ConfigHelper;

class EntityRelatedDecorator extends DecoratorEntityRelated
{
    public function priority(): int
    {
        return -100;
    }

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array
    {
        /*if (($config = (new ConfigHelper())->getConfig($index)) === null) {
            return $entitiesRelated;
        }*/

        return $entitiesRelated;
    }
}
