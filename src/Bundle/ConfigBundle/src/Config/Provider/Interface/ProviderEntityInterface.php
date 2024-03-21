<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;

interface ProviderEntityInterface extends ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
