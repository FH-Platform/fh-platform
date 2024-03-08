<?php

namespace FHPlatform\ConfigBundle\TaggedProvider;

use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;
use FHPlatform\ConfigBundle\Util\Sorter\PrioritySorter;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class TaggedProvider
{
    public function __construct(
        #[TaggedIterator('symfony_es.connection_provider')] private readonly iterable $providersConnection,
        #[TaggedIterator('symfony_es.provider.index')] private readonly iterable $providersIndex,
        #[TaggedIterator('symfony_es.provider.entity')] private readonly iterable $providersEntity,
        #[TaggedIterator('symfony_es.provider.entity_related')] private readonly iterable $providersEntityRelated,
        #[TaggedIterator('symfony_es.decorator.index')] private readonly iterable $decoratorsIndex,
        #[TaggedIterator('symfony_es.decorator.entity')] private readonly iterable $decoratorsEntity,
        private readonly PrioritySorter $prioritySorter,
    ) {
    }

    public function getProvidersConnection(): iterable
    {
        return $this->toArray($this->providersConnection);
    }

    public function getProvidersIndex(): iterable
    {
        return $this->toArray($this->providersIndex);
    }

    public function getProvidersEntity(): iterable
    {
        return $this->toArray($this->providersEntity);
    }

    public function getProvidersEntityRelated(): iterable
    {
        return $this->toArray($this->providersEntityRelated);
    }

    public function getDecoratorsIndex(): iterable
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsIndex));
    }

    public function getDecoratorsEntity(): iterable
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsEntity));
    }

    public function getIncludedClasses(): array
    {
        return [];
    }

    private function toArray(iterable $iterable)
    {
        $array = [];

        foreach ($iterable as $item) {
            if (0 === count($this->getIncludedClasses())) {
                $array[] = $item;
            } else {
                if (in_array(get_class($item), $this->getIncludedClasses())) {
                    $array[] = $item;
                }
            }
        }

        return $array;
    }

    public function firstConnectionProvider(): ConnectionProvider
    {
        foreach ($this->getProvidersConnection() as $connectionProvider) {
            return $connectionProvider;
        }

        // TODO
        throw new \Exception('Connection provider not found.');
    }
}
