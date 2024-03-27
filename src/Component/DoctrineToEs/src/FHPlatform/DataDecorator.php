<?php

namespace FHPlatform\Component\DoctrineToEs\FHPlatform;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntity;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\FHPlatform\Helper\ConfigHelper;

class DataDecorator extends DecoratorEntity
{
    public function __construct(
        private readonly DataBuilder $dataBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        if (($config = (new ConfigHelper())->getConfig($index)) === null) {
            return $data;
        }

        return array_merge($data, $this->dataBuilder->build($index, $entity, $config));
    }
}
