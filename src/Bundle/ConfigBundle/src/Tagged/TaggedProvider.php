<?php

namespace FHPlatform\ConfigBundle\Tagged;

use FHPlatform\ConfigBundle\Service\Sorter\PrioritySorter;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class TaggedProvider
{
    public static array $includedClasses = [];
    public static array $excludedClasses = [];

    public function __construct(
        #[TaggedIterator('fh_platform.config.provider.connection')] private readonly iterable $providersConnection,
        #[TaggedIterator('fh_platform.config.provider.index')] private readonly iterable $providersIndex,
        #[TaggedIterator('fh_platform.config.provider.entity')] private readonly iterable $providersEntity,
        #[TaggedIterator('fh_platform.config.provider.entity_related')] private readonly iterable $providersEntityRelated,
        #[TaggedIterator('fh_platform.config.decorator.index')] private readonly iterable $decoratorsIndex,
        #[TaggedIterator('fh_platform.config.decorator.entity')] private readonly iterable $decoratorsEntity,
        #[TaggedIterator('fh_platform.config.decorator.entity_related')] private readonly iterable $decoratorsEntityRelated,
        private readonly PrioritySorter $prioritySorter,
    ) {
    }

    public function getProvidersConnection(): array
    {
        return $this->toArray($this->providersConnection);
    }

    public function getProvidersIndex(): array
    {
        return $this->toArray($this->providersIndex);
    }

    public function getProvidersEntity(): array
    {
        return $this->toArray($this->providersEntity);
    }

    public function getProvidersEntityRelated(): array
    {
        return $this->toArray($this->providersEntityRelated);
    }

    public function getDecoratorsIndex(): array
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsIndex));
    }

    public function getDecoratorsEntity(): array
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsEntity));
    }

    public function getDecoratorsEntityRelated(): array
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsEntityRelated));
    }

    private function toArray(iterable $iterable): array
    {
        // TODO cache

        $includedClasses = self::$includedClasses;
        $excludedClasses = self::$excludedClasses;

        $tagged = [];
        foreach ($iterable as $item) {
            $className = get_class($item);

            if (in_array($className, $excludedClasses)) {
                continue;
            }

            if (0 === count($includedClasses)) {
                $tagged[] = $item;
            } else {
                if (in_array($className, $includedClasses)) {
                    $tagged[] = $item;
                }
            }
        }

        return $tagged;
    }

    public function firstConnectionProvider(): ProviderConnection
    {
        foreach ($this->getProvidersConnection() as $connectionProvider) {
            return $connectionProvider;
        }

        // TODO
        throw new \Exception('Connection provider not found.');
    }
}
