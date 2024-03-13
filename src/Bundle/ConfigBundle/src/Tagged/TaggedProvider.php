<?php

namespace FHPlatform\ConfigBundle\Tagged;

use FHPlatform\ConfigBundle\Service\Sorter\PrioritySorter;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;
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

    /** @return  ProviderConnection[] */
    public function getProvidersConnection(): array
    {
        return $this->toArray($this->providersConnection);
    }

    /** @return  ProviderIndexInterface[] */
    public function getProvidersIndex(): array
    {
        return $this->toArray($this->providersIndex);
    }

    /** @return  ProviderEntityInterface[] */
    public function getProvidersEntity(): array
    {
        return $this->toArray($this->providersEntity);
    }

    /** @return  ProviderEntityRelatedInterface[] */
    public function getProvidersEntityRelated(): array
    {
        return $this->toArray($this->providersEntityRelated);
    }

    /** @return DecoratorIndexInterface[] */
    public function getDecoratorsIndex(mixed $interface = null, ?string $className = null): array
    {
        return $this->prioritySorter->sort($this->filterDecorators($this->toArray($this->decoratorsIndex), $interface, $className));
    }

    public function getDecoratorsEntity(mixed $interface = null, ?string $className = null): array
    {
        return $this->prioritySorter->sort($this->filterDecorators($this->toArray($this->decoratorsEntity), $interface, $className));
    }

    public function getDecoratorsEntityRelated(mixed $interface = null, ?string $className = null): array
    {
        return $this->prioritySorter->sort($this->filterDecorators($this->toArray($this->decoratorsEntityRelated), $interface, $className));
    }

    private function filterDecorators(array $decorators, mixed $interface = null, ?string $className = null): array
    {
        if (!$interface or !$className) {
            return $decorators;
        }

        foreach ($decorators as $k => $decorator) {
            if ($decorator instanceof $interface and $decorator->getClassName() !== $className) {
                unset($decorators[$k]);
            }
        }

        return $decorators;
    }

    private function toArray(iterable $iterable): array
    {
        // TODO cache, move to service

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
}
