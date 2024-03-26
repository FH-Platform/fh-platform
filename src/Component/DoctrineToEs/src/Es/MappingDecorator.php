<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntity;
use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Mapper\AssociationsProvider;
use FHPlatform\Component\DoctrineToEs\Mapper\FieldsProvider;

class MappingDecorator extends DecoratorIndex
{
    public function priority(): int
    {
        return -100;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        return $mapping;
    }
}
