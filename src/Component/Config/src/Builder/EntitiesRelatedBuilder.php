<?php

namespace FHPlatform\Component\Config\Builder;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class EntitiesRelatedBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    public function build(Connection $connection, mixed $entity, string $type, $changedFields): array
    {
        if (($className = $this->persistence->getRealClassName($entity::class)) and !$this->persistence->isEntity($className)) {
            throw new \Exception('Given entity is not persistence entity');
        }

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntityRelated(ProviderBaseInterface::class, $className);

        // decorate entityRelated
        $entitiesRelated = $this->decorateEntitiesRelated($connection, $entity, $type, $changedFields, $decorators);

        return $entitiesRelated;
    }

    /** @param  DecoratorEntityRelatedInterface[] $decorators */
    private function decorateEntitiesRelated(Connection $connection, mixed $entity, string $type, array $changedFields, array $decorators): array
    {
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            $entitiesRelated = $decorator->getEntityRelatedEntities($connection, $entity, $type, $changedFields, $entitiesRelated);
        }

        return $entitiesRelated;
    }
}
