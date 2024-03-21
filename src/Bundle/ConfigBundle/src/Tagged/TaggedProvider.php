<?php

namespace FHPlatform\ConfigBundle\Tagged;

use FHPlatform\ConfigBundle\Service\Sorter\PrioritySorter;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;

class TaggedProvider
{
    public static array $includedClasses = [];
    public static array $excludedClasses = [];

    private PrioritySorter $prioritySorter;

    public function __construct(
        private readonly iterable $providersConnection,
        private readonly iterable $providersIndex,
        private readonly iterable $providersEntity,
        private readonly iterable $providersEntityRelated,
        private readonly iterable $decoratorsIndex,
        private readonly iterable $decoratorsEntity,
        private readonly iterable $decoratorsEntityRelated,
    ) {
        $this->prioritySorter = new PrioritySorter();
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

    /** @return DecoratorEntityInterface[] */
    public function getDecoratorsEntity(mixed $interface = null, ?string $className = null): array
    {
        return $this->prioritySorter->sort($this->filterDecorators($this->toArray($this->decoratorsEntity), $interface, $className));
    }

    /** @return DecoratorEntityRelatedInterface[] */
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
