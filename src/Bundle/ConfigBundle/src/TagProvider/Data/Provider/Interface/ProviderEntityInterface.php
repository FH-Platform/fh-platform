<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\IndexInterface;

interface ProviderEntityInterface extends IndexInterface, EntityInterface, EntityRelatedInterface, ProviderBaseInterface
{
}
