<?php

namespace FHPlatform\Component\FrameworkBridge;

interface BuilderInterface
{
    // build search engine (elasticsearch, meilisearch, etc, ...)
    public function buildSearchEngine(): void;

    // define persistence (doctrine, eloquent, ...)
    public function buildPersistence(): void;
}
