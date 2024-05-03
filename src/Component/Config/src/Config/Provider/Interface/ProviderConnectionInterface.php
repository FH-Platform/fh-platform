<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

interface ProviderConnectionInterface
{
    public function getName(): string;

    public function getIndexPrefix(): string;

    public function getClientConfig(): array;
}
