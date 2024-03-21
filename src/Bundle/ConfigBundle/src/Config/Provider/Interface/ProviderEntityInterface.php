<?php

namespace FHPlatform\ConfigBundle\Config\Provider\Interface;

use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;

interface ProviderEntityInterface extends ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
