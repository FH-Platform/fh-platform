<?php

namespace FHPlatform\Component\FrameworkBridge;

interface BuilderInterface
{
    // build search engine (elasticsearch, meilisearch, etc, ...)
    public function buildSearchEngine(): void;

    // define persistence (doctrine, eloquent, ...)
    public function buildPersistence(): void;

    // define message dispatcher (Symfony messenger, laravel queues, ...)
    public function buildMessageDispatcher(): void;

    // define event dispatcher (Symfony events, laravel events, ...)
    public function buildEventDispatcher(): void;
}
