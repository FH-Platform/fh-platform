<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.decorator.entity')]
interface DecoratorEntityInterface extends PriorityInterface
{
    public function getEntityData(mixed $entity, array $data, array $mapping): array;

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool;
}
