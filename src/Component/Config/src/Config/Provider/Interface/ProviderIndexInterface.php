<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

interface ProviderIndexInterface
{
    public function getClassName(): string;

    public function getConnection(): string;

    public function getIndexName(string $className): string;
}
