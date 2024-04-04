<?php

namespace FHPlatform\Component\Config\DTO;

class Documents
{
    public function __construct(
        private readonly Index $index,
        private readonly mixed $identifier,
        private readonly array $data,
        private readonly string $type,
    ) {
    }
}
