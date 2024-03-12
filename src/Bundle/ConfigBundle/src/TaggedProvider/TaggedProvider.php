<?php

namespace FHPlatform\ConfigBundle\TaggedProvider;

use FHPlatform\ConfigBundle\Service\Sorter\PrioritySorter;
use FHPlatform\ConfigBundle\TagProvider\Connection\ConnectionProvider;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class TaggedProvider
{
    public function __construct(
        #[TaggedIterator('fh_platform.config.tagged.provider.connection')] private readonly iterable $providersConnection,
        #[TaggedIterator('fh_platform.config.tagged.provider.index')] private readonly iterable $providersIndex,
        #[TaggedIterator('fh_platform.config.tagged.provider.entity')] private readonly iterable $providersEntity,
        #[TaggedIterator('fh_platform.config.tagged.provider.entity_related')] private readonly iterable $providersEntityRelated,
        #[TaggedIterator('fh_platform.config.tagged.decorator.index')] private readonly iterable $decoratorsIndex,
        #[TaggedIterator('fh_platform.config.tagged.decorator.entity')] private readonly iterable $decoratorsEntity,
        #[TaggedIterator('fh_platform.config.tagged.decorator.entity_related')] private readonly iterable $decoratorsEntityRelated,
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

    public function getDecoratorsEntityRelated(): iterable
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsEntityRelated));
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
