<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface;

interface ProviderBaseInterface
{
    public function getClassName(): string;

    public function getConnection(): string;

    public function getAdditionalConfig(): array;
}
