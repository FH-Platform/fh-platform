<?php

namespace FHPlatform\ConfigBundle\DTO;

class IndexMappingItem
{
    public function __construct(
        private readonly string $className,
        private readonly Connection $connection,
        private readonly string $name,
        private readonly array $mapping,
        private readonly array $settings,
        private readonly array $additionalConfig,
    ) {
    }
}
