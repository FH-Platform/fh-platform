<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorConnectionTrait;

abstract class DecoratorConnection implements DecoratorConnectionInterface
{
    use DecoratorConnectionTrait;
}
