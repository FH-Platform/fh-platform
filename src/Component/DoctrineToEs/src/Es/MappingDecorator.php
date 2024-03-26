<?php

namespace FHPlatform\Component\DoctrineToEs\Es;

use FHPlatform\Component\Config\Config\Decorator\DecoratorIndex;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\Es\Helper\ConfigHelper;

class MappingDecorator extends DecoratorIndex
{
    public function __construct(
        private readonly MappingBuilder $mappingBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        if (($config = (new ConfigHelper())->getConfig($index)) === null) {
            return $mapping;
        }

        return array_merge($mapping, $this->mappingBuilder->build($index, $config));
    }
}
