<?php

namespace FHPlatform\Component\Config\Config;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Config\Provider\ProviderConnection;
use FHPlatform\Component\Config\Util\Sorter\PrioritySorter;

class ConfigProvider
{
    public static array $includedPatterns = [];
    public static array $excludedPatterns = [];
    public static array $includedClasses = [];
    public static array $excludedClasses = [];

    private PrioritySorter $prioritySorter;

    public function __construct(
        private readonly iterable $providersConnection,
        private readonly iterable $providersIndex,
        private readonly iterable $providersEntity,
        private readonly iterable $providersEntityRelated,
        private readonly iterable $decoratorsConnection,
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

    /** @return DecoratorConnectionInterface[] */
    public function getDecoratorsConnection(): array
    {
        return $this->prioritySorter->sort($this->toArray($this->decoratorsConnection));
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

        $taggedConfigClasses = [];
        foreach ($iterable as $item) {
            $className = get_class($item);

            if (count(self::$includedClasses) > 0) {
                if (in_array($className, self::$includedClasses)) {
                    $taggedConfigClasses[] = $item;
                }
            } elseif (count(self::$includedPatterns) > 0) {
                foreach (self::$includedPatterns as $includedPattern) {
                    if (str_starts_with($className, $includedPattern)) {
                        $taggedConfigClasses[] = $item;
                    }
                }
            } else {
                $taggedConfigClasses[] = $item;
            }
        }

        foreach ($taggedConfigClasses as $key => $item) {
            $className = get_class($item);

            if (count(self::$excludedClasses) > 0) {
                if (in_array($className, self::$excludedClasses)) {
                    unset($taggedConfigClasses[$key]);
                }
            }

            if (count(self::$excludedPatterns) > 0) {
                foreach (self::$excludedPatterns as $excludedPattern) {
                    if (str_starts_with($className, $excludedPattern)) {
                        unset($taggedConfigClasses[$key]);
                    }
                }
            }
        }

        return $taggedConfigClasses;
    }
}
