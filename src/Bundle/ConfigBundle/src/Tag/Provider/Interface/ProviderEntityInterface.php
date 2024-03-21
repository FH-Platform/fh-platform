<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;

interface ProviderEntityInterface extends ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
