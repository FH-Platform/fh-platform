<?php

namespace FHPlatform\Component\Config\Config\Provider\Interface;

interface ProviderBaseInterface
{
    public function getConnectionName(): string;

    public function getClassName(): string;
}
